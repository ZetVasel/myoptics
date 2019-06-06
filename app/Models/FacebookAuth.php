<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 22.02.2017
 * Time: 10:49
 */

//////////////////////////////////
//* Класс Авторизации через FB *//
//*  Author: Nikolay Tsurkan   *//
//////////////////////////////////

namespace App\Models;

use App\User;
use Auth;
use Session;

class FacebookAuth
{
    private static $client_id = "2160413427569654"; // ID приложения
    private static $client_secret = "be782e2a18a4784b11df17933c63cd5d"; // Защищённый ключ
    private static $redirect_url = "https://myoptics.com.ua/facebook-auth"; // Адрес сайта
    private static $getUrl = "https://www.facebook.com/dialog/oauth";//ссылка на которую будут передаваться гет параметры

    //генерация линка для авторизации
    public static function generateLink(){
        $params = array(
            'client_id'     => self::$client_id,
            'redirect_uri'  => self::$redirect_url,
            'response_type' => 'code',
            'scope'         => 'email'
        );
        $link =  self::$getUrl.'?'.urldecode(http_build_query($params));
        return $link;
    }

    //получение токена
    private function getToken($code){
        $params = array(
            'client_id'     => self::$client_id,
            'client_secret' => self::$client_secret,
            'code'          => $code,
            'redirect_uri'  => self::$redirect_url,
            'scope'         => 'email'
        );

        $url = 'https://graph.facebook.com/oauth/access_token';

        $token = null;
        parse_str(file_get_contents($url . '?' . http_build_query($params)), $token);

        if(count($token) > 0){
            return $token;
        }else{
            return -1;
        }
    }


    //получение массива с данными пользователя
    public function getUserInfo($code){
        $token = $this->getToken($code);
        if($token == -1) {
            echo 'Error get token!';
            exit;
        }


        $token = (array)json_decode(key($token));

        $params = array(
            'client_id' => self::$client_id,
            'client_secret' => self::$client_secret,
            'code' => $code,
            'redirect_uri' => self::$redirect_url,
            'scope'         => 'email,read_stream',
            'access_token' => $token['access_token']
        );


        $resultFlag = false;

        $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

        if(isset($userInfo['id'])){
            $resultFlag = true;
        }

        if($resultFlag){
            return $userInfo;
        }else{
            return -1;
        }

    }

    //проверка зарегестрирован ли пользователь
    private function isRegister($fbId){
        if($user = User::whereFacebookid($fbId)->first()){
            return $user;
        }else{
            return false;
        }
    }

    //функция регистрации
    private function setRegister($userInfoArray, $password){
        $name = explode(' ',$userInfoArray['name']);
        $user = User::create([
            'firstName' => $this->rus2translit($name[0]),
            'lastName' => $this->rus2translit($name[1]),
            'password' => bcrypt($password),
            'facebookid' => $userInfoArray['id'],
            'activated' => 1,
            'permissions' => 'user'
        ]);
        return $user;
    }

    //авторизация пользователя
    public function setAuthSession($code){
        $userInfo = $this->getUserInfo($code);
        if($userInfo == -1) {
            echo 'Error get user information!';
            exit;
        }
        //ели пользователь зареган
        $user = $this->isRegister($userInfo['id']);
        if($user){
            $profile = User::find($user->id);
            Auth::login($profile);

        }else{
            //генерируем 6-ти значный пароль
            $password = $this->generatePassword(6);
            //регистрируем
            $newUserId = $this->setRegister($userInfo, $password);
            $profile = User::find($newUserId->id);
            Auth::login($profile);
        }

        return 1;
    }

    //транслитерация
    public function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    //Генерация пароля
    public function generatePassword($length = 6){
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
}