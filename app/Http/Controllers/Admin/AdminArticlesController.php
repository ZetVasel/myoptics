<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Articles;
use App\Http\Requests;
use App\Http\Requests\ArticlesRequest;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminArticlesController extends AdminBaseController {

	public function getIndex()
	{
		$title = 'Статьи';
		$articles_count = Articles::count();
		$articles = Articles::latest()->paginate(15);
		return view('admin.showArticles', compact(['articles','articles_count', 'title']));
	}


	public function postIndex( Request $request ) {

		$ids = $request->get('check');
		// если действие для удаления
		if( $request->get('action') == 'delete' ) {
			// удалим из бд
			Articles::destroy( $ids );
		}
		return redirect()->back();
	}


	public function getAdd()
	{
		$post = new Articles;
		$title = 'Создание статьи';
		return view('admin.editArticlesPost', compact(['post','title']));
	}


	public function postAdd( ArticlesRequest $request )
	{
		$post = Articles::create($request->all());

		return redirect('master/articles')->with('success', 'Статья успешно добавлена!');
	}


	public function getEdit( $id )
	{
		$post = Articles::find($id);
		$title = 'Редактирование статьи';
		return view('admin.editArticlesPost', compact(['post', 'title']));
	}


	public function postEdit( ArticlesRequest $request, $id )
	{
		$articles = Articles::findOrFail($id);
		$articles->update($request->all());

		return redirect('/master/articles/edit/' . $id)->with('success', 'Статья успешно обновлена!');
	}
}