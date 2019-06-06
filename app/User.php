<?php

namespace App;

use App\Models\Orders;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['remember_token', 'persist_code', 'reset_password_code'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public static function getOrderedSum( $id ){
        $orders = Orders::with(['orderedproducts' => function($query){

            $query->leftJoin('dv_products', 'dv_orders_products.product_id', '=', 'dv_products.id')
                ->select(['dv_orders_products.*', 'dv_products.imgs', 'dv_products.slug','dv_products.name','dv_products.main_img'])
                ->get();
        }])
            ->whereUserId( $id )->get();
        $orders_ids = [];
        $total_ordered = 0;
        foreach( $orders as $ord ){
            $total_ordered += $ord->total_cost;
            array_push($orders_ids, $ord->id);
        }
        return $total_ordered;
    }

}
