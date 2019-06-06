@extends('admin.layout')

@section('main')

	<div class="row pages">

		<div class="col-sm-12">
			<h1>{{ $title }}</h1>
		</div>

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/product_reviews">
				<i class="fa fa-reply-all fa-3x"></i>
				<span>Отзывы к товарам <strong>({{ $product_rev }})</strong></span>
			</a>
		</div>

		<div class="col-md-2 col-sm-3 col-xs-4 animated flipInX">
			<a href="/master/feedback">
				<i class="fa fa-cogs fa-3x"></i>
				<span>Сервисные заявки <strong>({{ $feedbacks }})</strong></span>
			</a>
		</div>

	</div>
	<!-- pages end -->

@stop