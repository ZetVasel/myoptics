<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 29.05.2017
 * Time: 10:19
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CharlistOptions extends Model
{
    protected $table = 'dv_charlist_options';
    protected $guarded = [];
    public $timestamps = false;


    // возвращает выбранные опции товара
    public static function getOptions( $id ){

        $options_all = self::whereProduct_id( $id )->get();
        $options = [];

        foreach( $options_all as $option ){
            if( isset($options[$option->charlist_id]) )
                array_push($options[$option->charlist_id], $option->value );
            else
                $options[$option->charlist_id] = [$option->value];
        }
        return $options;
    }


    // сохраняет опции товара
    public static function saveOptions( $id, $feature ){


        self::whereProduct_id( $id )->delete();


        if(isset($feature)){
            foreach ($feature as $f_id => $f_values) {
                foreach($f_values as $f_v){
                    if( $f_v != '' )
                        $for_insert[] = ['charlist_id' => $f_id, 'product_id' => $id, 'value' => $f_v ];
                }
            }
        }

        if( isset( $for_insert ) )
            self::insert( $for_insert );
    }
    // reсохраняет опции товара
    public static function resaveOptions( $id, $feature ){
        // $for_insert[] = '';
        foreach ($feature as $feat) {
            $for_insert[] = ['charlist_id' => $feat->charlist_id, 'product_id' => $id, 'value' => $feat->value];
        }

        if( isset( $for_insert ) )
            Options::insert( $for_insert );
    }
}