<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
	protected $table = 'dv_features';
	protected $guarded = [];

	public function options() {
		return $this->hasMany( 'App\Models\Options' , 'feature_id' );
	}

	public function variants() {
		return $this->hasMany( 'App\Models\OptionVariants' , 'feature_id' );
	}
}
