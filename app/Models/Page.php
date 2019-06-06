<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;

class Page extends Node
{
	protected $table = 'dv_pages';
	protected $guarded = [];



	public static function main_pages_tree()
	{
		$pages = Page::where('type', '=', 'main')->where('slug', '!=', '/')->orderBy('id')->get();
		foreach($pages as &$item) $main_pages_tree[$item->parent_id][] = &$item;
			unset($item);

		foreach($pages as &$item) if (isset($main_pages_tree[$item->id]))
			$item['subpages'] = $main_pages_tree[$item->id];
		
		return reset( $main_pages_tree );
	}

}
