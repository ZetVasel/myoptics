<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\News;

use App\Http\Requests;
use App\Http\Requests\NewsRequest;
// use App\Http\Requests\AddNewsRequest;
use File;

class AdminNewsController extends AdminBaseController {

	public function getIndex()
	{
		$title = 'Новости';
		$news_count = News::count();
		$news = News::latest()->paginate(15);
		return view('admin.showNews', compact(['news','news_count', 'title']));
	}


	public function postIndex( Request $request ) {

		$ids = $request->get('check');
		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			// удалим из бд
			News::destroy( $ids );
		}
		return redirect()->back();
	}


	public function getAdd()
	{
		$post = new News;
		$title = 'Создание Новостией';
		$post->image = '';
		return view('admin.editNewsPost', compact(['post','title']));
	}


	public function postAdd( NewsRequest $request )
	{
		$post = News::create( $request->except('image') );

		if( $image = $request->file('image') )
			News::saveImage( $image, $post->id );

		return redirect('master/news')->with('message', 'Новость успешно добавлена!');
	}


	public function getEdit( $id )
	{
		$post = News::find($id);
		$title = 'Редактирование Новостией';
		return view('admin.editNewsPost', compact(['post', 'title']));
	}


	public function postEdit( NewsRequest $request, $id )
	{
		$news = News::findOrFail($id);
		$news->update( $request->except('image') );

		if( $image = $request->file('image') )
			News::updateImage( $image, $id );

		return redirect('/master/news/edit/' . $id)->with('message', 'Новость успешно обновлена!');
	}
}