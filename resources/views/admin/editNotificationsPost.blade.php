@extends('admin.layout')

@section('main')

	<h1><a href="/master/notify" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1><br>
	
	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
	@endif

	@include('errors.formErrors')

	{!! Form::model( $post, array( 'class' => 'form-horizontal', 'role' => 'form') ) !!}


	<div class="form-group">
		{!! Form::label('body', 'Название', array('class'=>'col-sm-2 control-label') ) !!}
		<div class="col-sm-10">
			{!! Form::text('body',  $post->body, array('class'=>'form-control') ) !!}
		</div>
	</div>
	
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-success">Сохранить</button>
		</div>
	  </div>


	{!! Form::close() !!}

@stop
