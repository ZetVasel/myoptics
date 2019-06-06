@extends('admin.layout')

@section('main')

	<div class="row pages">

		<div class="col-sm-12">
			<h1>{{ $title }}</h1>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/orders/show/new">
				<i class="fa fa-cart-arrow-down fa-3x"></i>
				<span>Новые заказы <strong>({{ $new_orders }})</strong></span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/orders/show/taken">
				<i class="fa fa-external-link fa-3x"></i>
				<span>Принятые заказы <strong>({{ $accept_orders }})</strong></span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/orders/show/made">
				<i class="fa fa-check-square-o fa-3x"></i>
				<span>Выполненные заказы <strong>({{ $closed_orders }})</strong></span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/orders/show/canceled">
				<i class="fa fa-ban fa-3x"></i>
				<span>Отмененные заказы <strong>({{ $canceled_orders }})</strong></span>
			</a>
		</div>

	</div>
	<!-- pages end -->
@stop