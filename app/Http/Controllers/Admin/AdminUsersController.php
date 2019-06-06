<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;
use App\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Hash;

class AdminUsersController extends AdminBaseController
{
    public function getIndex()
    {
        $title = 'Пользователи';
		$users = User::orderBy('permissions', 'asc')->paginate(15);
		$users_count = User::all()->count();

        return view('admin.showUsers', compact(['title', 'users', 'users_count']));
    }


	public function postIndex( Request $request ) {

		$ids = $request->get('check');
		// если удаление
		if( $request->get('action') == 'delete' )
			User::destroy( $ids );

		return redirect()->back();
	}


	public function getEdit( $id ) {

		$title = "Редактирование пользователя";
		$post = User::find( $id );

		return view('admin.editUsers', compact(['title', 'post']));
	}


	public function postEdit( Request $request, $id ){

		$user = User::find($id);
		$user->email = $request->get('email');
		$user->name = $request->get('name');
		$user->surname = $request->get('surname');
		$user->phone = $request->get('phone');
		$user->permissions = $request->get('permissions');
		
		if( $request->get('password') != "" )
			$user->password = Hash::make( $request->get('password') );

		$user->save();

		return redirect()->back()->with('success', 'Информация успешно обновлена');
	}



	public function postAdd( Request $request ){


		$user = User::create([
			'email'   		=> $request->get('email'),
			'name'			=> $request->get('name'),
			'surname'		=> $request->get('surname'),
			'phone'			=> $request->get('phone'),
			'password' 		=> Hash::make($request->get('password')),
			'activated'		=> 1,
			'permissions'	=> $request->get('permissions')
		]);

		return redirect('/master/users');
	}

	public function getAdd() {

		$post = new User;
		$title = "Добавление пользователя";
		
		return view('admin.editUsers', compact(['title', 'post']));
	}




}
