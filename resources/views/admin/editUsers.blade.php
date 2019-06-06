@extends('admin.layout')

@section('main')

	<h1><a href="/master/users" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1>
	<br>
	
	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
	@endif

	@include('errors.formErrors')

	{!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ) ) !!}

		<div class="form-group">
			{!! Form::label('permissions', 'Тип пользователя', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-3">
				{!! Form::select('permissions', array('admin' => 'Администратор'), $post->permissions, array( 'class'=>'form-control')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('name', 'Имя', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::text('name',  $post->name, array('class'=>'form-control') ) !!}
			</div>
		</div><div class="form-group">
			{!! Form::label('surname', 'Фамилия', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::text('surname',  $post->surname, array('class'=>'form-control') ) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('email', 'E-mail', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::text('email',  $post->email, array('class'=>'form-control') ) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('password', 'Пароль', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::password('password', array('class'=>'form-control') ) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('phone', 'Телефон', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::text('phone',  $post->phone, array('class'=>'form-control') ) !!}
			</div>
		</div>



		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-success">Сохранить</button>
			</div>
		</div>

	{!! Form::close() !!}

@stop
