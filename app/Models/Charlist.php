<?php
/**
 * Created by PhpStorm.
 * User: Dvacom
 * Date: 29.05.2017
 * Time: 10:13
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Charlist extends Model
{
    protected $table = 'dv_charlist';
    protected $guarded = [];

    public function options() {
        return $this->hasMany( 'App\Models\CharlistOptions' , 'charlist_id' );
    }

    public function variants() {
        return $this->hasMany( 'App\Models\CharlistOptionVariants' , 'charlist_id' );
    }
}