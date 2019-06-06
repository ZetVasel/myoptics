<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class AdminCategoriesController extends AdminBaseController
{
    public function getIndex(){

		$title = 'Категории';

		$categories = json_encode(array_values( Category::select(['id', 'parent_id', 'lft', 'rgt', 'depth', 'name'])
								->get()->toHierarchy()->toArray() ));

		$categories_count = Category::count();
        return view('admin.showCategories', compact(['title', 'categories', 'categories_count']));
    }



	public function postIndex( Request $request ) {

        if($request->ajax()){
            if( $request->has('block') ) {
                $block = $request->get('block');
                $show = (int)$request->get('show');
                $id = $request->get('id');
                switch ($block){
                    case 'show_in_block_1' :
                        if($show == 1){
                            if($oldShowBlock = Category::where('show_block_1', '=', $show)->first()){
                                Category::where('id', '=', $oldShowBlock->id)->update(['show_block_1' => 0]);
                            }
                        }
                        Category::where('id', '=', $id)->update(['show_block_1' => $show]);
                        break;

                    case 'show_in_block_2' :

                        if($show == 1){
                            if($oldShowBlock = Category::where('show_block_2', '=', $show)->first()){
                                Category::where('id', '=', $oldShowBlock->id)->update(['show_block_2' => 0]);
                            }
                        }

                        Category::where('id', '=', $id)->update(['show_block_2' => $show]);
                        break;
                    case 'show_in_block_3' :

                        if($show == 1){
                            if($oldShowBlock = Category::where('show_block_3', '=', $show)->first()){
                                Category::where('id', '=', $oldShowBlock->id)->update(['show_block_3' => 0]);
                            }
                        }

                        Category::where('id', '=', $id)->update(['show_block_3' => $show]);
                        break;
                    default:
                        return 'error';
                }
                return 'ok';
            }
        }

		$ids = $request->get('check');

		// удаляем
		if( $request->get('action') == 'delete' )
			Category::destroy( $ids );
		// обновляем позиции
		elseif( $request->get('action') == 'rebuild' ) {
			Category::rebuildTree( $request->get('data') );
			return 'rebuilded';
		}
		return redirect()->back();
	}


	public function getAdd(){

		$tree = Category::getNestedList('name', null, '&nbsp;&nbsp;&nbsp;');
		// $tree = Category::select('id', 'name')->where('depth', '0')->get()->toArray();
		
		$tree = array( '0' => '-') + $tree;
		// print_r($tree); exit;
		$parent_id = 0;

        $post = new Category;

		$post->image = "";
        $title = "Добавление категории";
        return view('admin.editCategory', compact([ 'title', 'post', 'tree', 'parent_id' ]));
	}


	public function postAdd( CategoryRequest $request ){

		if( $request->get('parent_id') == 0 )
			$input = $request->except( ['_token', 'parent_id', 'image']);
		else
			$input = $request->except( ['_token', 'image']); 

		$category = Category::create( $input );
		if ( $request->parent_id > 0 ){
			$category->makeChildOf( Category::whereId($request->parent_id)->first() );
		}
		if( $image = $request->file('image') )
			Category::saveImage( $image, $category->id );

		return redirect('/master/categories')->with('success', 'Запись добавлена');
	}


	public function getEdit( $id ){

		$post = Category::find( $id );
		$tree = Category::getNestedList('name', null, '&nbsp;&nbsp;&nbsp;');

		// уберем себя из списка
		unset( $tree[$id] );
		$tree = array( '0' => '-') + $tree;

		$parent_id = $post->parent_id;

		$title = "Редактирование категории";
		return view('admin.editCategory', compact(['title', 'post', 'tree', 'parent_id']));
	}


	public function postEdit( CategoryRequest $request, $id ){

		if( $request->get('parent_id') == 0 )
			$input = $request->except( ['_token', 'parent_id', 'image']);
		else
			$input = $request->except( ['_token', 'image']);

		Category::where('id', '=', $id)->update( $input );

        if( $image = $request->file('image') )
            Category::updateImage( $image, $id );

		return redirect()->back()->with('success', 'Запись обновлена');
	}
}