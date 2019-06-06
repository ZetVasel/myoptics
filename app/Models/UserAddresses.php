<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    protected $table = 'dv_user_address';
    protected $guarded = [];
    public $timestamps = false;
}
