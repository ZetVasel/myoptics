@extends('admin.layout')
@section('main')

	<div class="row pages">
		<div class="col-sm-12">
			<h1>{{ $title }}</h1>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/categories">
				<i class="fa fa-sitemap fa-3x"></i>
				<span>Категории</span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/products">
				<i class="fa fa-cubes fa-3x"></i>
				<span>Товары</span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/brands">
				<i class="fa fa-bookmark-o fa-3x"></i>
				<span>Бренды</span>
			</a>
		</div>
		{{--<div class="col-md-2 col-sm-3 col-xs-4">--}}
			{{--<a href="/master/offers">--}}
				{{--<!-- <i class="fa fa-bullhorn" aria-hidden="true"></i> -->--}}
				{{--<i class="fa fa-bullhorn fa-3x"></i>--}}
				{{--<span>Акции</span>--}}
			{{--</a>--}}
		{{--</div>--}}
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/delivery">
				<i class="fa fa-plane fa-3x"></i>
				<span>Способ доставки</span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/payment">
				<i class="fa fa-qrcode fa-3x"></i>
				<span>Способ оплаты</span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/features">
				<i class="fa fa-leaf fa-3x"></i>
				<span>Параметры фильтрации</span>
			</a>
		</div>

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/charlist">
				<i class="fa fa-list-ul fa-3x"></i>
				<span>Характеристика заказа</span>
			</a>
		</div>
	</div>
	<!-- pages end -->
@stop