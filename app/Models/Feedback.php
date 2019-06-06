<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File;

class Feedback extends Model
{
	protected $table = 'dv_feedbacks';
	protected $guarded = [];

	public static function saveFile($request, $id){
        $item = self::whereId($id)->first();
        $imgs = ['png', 'jpg', 'gif', 'jpeg'];
        $vid = ['mp4', 'mov', 'mpg', 'flv','avi', 'wmv'];
        foreach( $request->file('file') as $file ) {


            if(!in_array($file->getClientOriginalExtension(), $imgs) && !in_array($file->getClientOriginalExtension(), $vid)){
                print_r('Error File Extension');exit();
            }


            $filename = time() . '_'.$file->getClientOriginalName();
            $filename = self::transliterate($filename);
            $file->move('uploads/serviceFiles', $filename);
            DvFeedbacksFiles::create(['name' => $filename, 'feedback_id' => $item->id]);

        }
    }


    // Транслитерация строк.
    protected static function rus2translit($string) {
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

    public static function transliterate($str) {
        // переводим в транслит
        $str = self::rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);

        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }


    public static function destroy($ids)
    {
        $files = DvFeedbacksFiles::whereIn('feedback_id', $ids)->get();
        foreach( $files as $file ){
            File::delete( public_path() .'/uploads/serviceFiles/' . $file->name );
        }
        parent::destroy($ids);

    }

}
