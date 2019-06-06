@extends('admin.layout')

@section('main')

	<div class="row pages">
		<div class="col-sm-12">
			<h1>{{ $title }}</h1>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4">
			<a href="/master/delivery">
				<i class="fa fa-truck fa-3x"></i>
				<span>Доставка</span>
			</a>
		</div>
		<div class="col-md-2 col-sm-3 col-xs-4">
			<a href="/master/payment">
				<i class="fa fa-credit-card fa-3x"></i>
				<i class="fa fa-money fa-3x"></i>
				<i class="fa fa-cc-paypal fa-3x"></i>
				<span>Оплата</span>
			</a>
		</div>
	</div>

	<!-- pages end -->

@stop