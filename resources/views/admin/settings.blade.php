@extends('admin.layout')

@section('main')

	<h1>{{ $title }}</h1><br>
	<ul class="nav nav-tabs">
		<li class="active"><a href="/master/settings">Основные</a></li>
	</ul>


	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
	@endif

	{!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form',  'files' => true  ) ) !!}

		<div class="form-group" >
			{!! Form::label('logo', 'Логотип', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::file('logo', array('class' => 'filestyle', 'data-value'=> $settings->logo, 'data-buttonText' => 'Выбрать файл', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false' ) ) !!}
			</div>
			<div class="col-sm-2"></div>
			<div class="col-sm-10" style="margin: 10px 0;">
				<img src="/uploads/logo/{{$settings->logo}}">
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('footText', 'Текст в футере сайта', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::textarea('footText',  $settings->footText, array('class'=>'form-control editor') ) !!}
			</div>
		</div>


	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Время изменения слайдов на главной (в сек)(0 - слайды менятся не будут)</label></div>
		<div class="col-sm-10">
			{!! Form::text('delay',  $settings->delay, array('class'=>'form-control') ) !!}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Копирайт</label></div>
		<div class="col-sm-10">
			{!! Form::text('copir',  $settings->copir, array('class'=>'form-control') ) !!}
		</div>
	</div>

		<h3 class="text-center">Контактная информация</h3>

		<div class="form-group">
			<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Контактный телефон</label></div>
			<div class="col-sm-10">
				{!! Form::text('phone1',  $settings->phone1, array('class'=>'form-control') ) !!}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Контактный телефон</label></div>
			<div class="col-sm-10">
				{!! Form::text('phone2',  $settings->phone2, array('class'=>'form-control') ) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">e-Mail</label></div>
			<div class="col-sm-10">
				{!! Form::text('email',  $settings->email, array('class'=>'form-control') ) !!}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Адрес</label></div>
			<div class="col-sm-10">
				{!! Form::text('address',  $settings->address, array('class'=>'form-control') ) !!}
			</div>
		</div>

	<h3 class="text-center">Скидка</h3>


	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;" for="sum">Необходамая сумма покупок(грн)</label></div>
		<div class="col-sm-10">
			<input id="sum" type="number" class="form-control" name="sum" min="0" max="100000" value="{{ $settings->sum }}"><br>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;" for="discount">Скидка в %</label></div>
		<div class="col-sm-10">
			<input id="discount" type="number" class="form-control" name="discount" min="0" max="100" value="{{ $settings->discount }}"><br>
		</div>
	</div>




						
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-success">Сохранить</button>
			</div>
		</div>

	{!! Form::close() !!}
	@include('admin.tinymce_init')
@stop