<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 22.02.2017
 * Time: 9:02
 */
///////////////////////////////////////
//*  Класс Авторизации через Google *//
//*     Author: Nikolay Tsurkan     *//
///////////////////////////////////////

namespace App\Models;
use App\User;
use Auth;
use Session;

class GoogleAuth
{
    private static $client_id = "450909999417-l92hp0ocfg6nt5iquqecfccq6n6deagq.apps.googleusercontent.com"; // ID приложения
    private static $client_secret = "GqdqYDB_LpwgcGKO07MhS3IT"; // Защищённый ключ
    private static $redirect_url = "https://myoptics.com.ua/google-auth"; // Адрес сайта
    private static $getUrl = "https://accounts.google.com/o/oauth2/auth";//ссылка на которую будут передаваться гет параметры

    //генерация линка для авторизации
    public static function generateLink(){
        $params = array(
            'redirect_uri'  => self::$redirect_url,
            'response_type' => 'code',
            'client_id'     => self::$client_id,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );
        $link =  self::$getUrl.'?'.urldecode(http_build_query($params));
        return $link;
    }

    //получение токена
    private function getToken($code){
        $params = array(
            'client_id' => self::$client_id,
            'client_secret' => self::$client_secret,
            'grant_type'    => 'authorization_code',
            'code' => $code,
            'redirect_uri' => self::$redirect_url
        );
        $url = 'https://accounts.google.com/o/oauth2/token';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        if($tokenInfo = json_decode($result, true)){
            return $tokenInfo;
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
        $params = array(
            'client_id' => self::$client_id,
            'client_secret' => self::$client_secret,
            'grant_type'    => 'authorization_code',
            'code' => $code,
            'redirect_uri' => self::$redirect_url,
            'access_token' => $token['access_token']
        );


        $resultFlag = false;

        //получаем массив с данными пользователя
        $userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo'.'?'.urldecode(http_build_query($params))), true);

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
    private function isRegister($googleId){
        if($user = User::whereGoogleid($googleId)->first()){
            return $user;
        }else{
            return false;
        }
    }


    private function isDublicate($email){
    	if(!empty($email)){
    		if($user = User::whereEmail($email)->first()){
            	return $user;
        	}
    	}
    	return false;
    }

    //функция регистрации
    private function setRegister($userInfoArray, $password){

    	$user = $this->isDublicate($userInfoArray['email']);

 		if($user){
            $profile = User::find($user->id);
            Auth::login($profile);
        }else{
	        $user = User::create([
	            'firstName' => $this->rus2translit($userInfoArray['given_name']),
	            'lastName' => $this->rus2translit($userInfoArray['family_name']),
	            'password' => bcrypt($password),
	            'googleid' => $userInfoArray['id'],
	            'email' => $userInfoArray['email'],
	            'activated' => 1,
	            'permissions' => 'user'
	        ]);
        }
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