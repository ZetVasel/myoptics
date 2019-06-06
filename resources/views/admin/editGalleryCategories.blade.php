@extends('admin.layout')

@section('main')

	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
	@endif
	@include('errors.formErrors')
	 <h1><a href="/master/gallery-categories" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1>
		<!--  -->
		{!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true) ) !!}
			<div class="form-group">
				{!! Form::label('name', 'Название', array('class'=>'col-sm-2 control-label') ) !!}
				<div class="col-sm-10">
					{!! Form::text('name', $gallery_cat->name, array('class'=>'form-control', 'required') ) !!}
				</div>
			</div>
		<!--  -->
		<div class="form-group">
			{!! Form::label('img', 'Иконка', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10" >
			{!! Form::file('img', array('class' => 'filestyle', 'data-value'=>$gallery_cat->img, 'data-buttonText' => 'Выберите файл', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false' , 'multiple' => 'true') ) !!}
			</div>
		</div>	
		<!--  -->

			<div class="form-group" >
				<div class="col-sm-offset-2 col-sm-10">
			  		<button type="submit" class="btn btn-success">Сохранить</button>
				</div>
			</div>
		{!! Form::close() !!}	
@section('scripts')

@endsection

@include('admin.tinymce_init')
@endsection