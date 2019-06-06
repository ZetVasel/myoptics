@extends('admin.layout')

@section('main')

	<div class="row pages">

		<div class="col-sm-12">
			<h1>Управление контентом</h1>
		</div>

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/pages/main">
				<i class="fa fa-globe fa-3x"></i>
				<span>Страницы основного меню</span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/pages/bay">
				<i class="fa fa-users fa-3x"></i>
				<span>Страницы покупателям</span>
			</a>
		</div>
		{{--<div class="col-md-2 col-sm-3 col-xs-4">--}}
			{{--<a href="/master/pages/catalog">--}}
				{{--<i class="fa fa-calendar fa-3x"></i>--}}
				{{--<span>Страницы "КАТАЛОГ"</span>--}}
			{{--</a>--}}
		{{--</div>--}}

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/news">
				<i class="fa fa-newspaper-o fa-3x"></i>
				<span>Новости</span>
			</a>
		</div>


		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/pointers">
				<i class="fa fa-map-marker fa-3x"></i>
				<span>Маркеры на карте</span>
			</a>
		</div>

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/notifications">
				<i class="fa fa-bell fa-3x"></i>
				<span>Напоминания</span>
			</a>
		</div>

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/slider">
				<i class="fa fa-picture-o fa-3x"></i>
				<span>Слайдер</span>
			</a>
		</div>
		{{--<div class="col-md-2 col-sm-3 col-xs-4">--}}
			{{--<a href="/master/pages/other">--}}
				{{--<i class="fa fa-delicious fa-3x"></i>--}}
				{{--<span>Другие страницы</span>--}}
			{{--</a>--}}
		{{--</div>--}}
	</div>

@stop