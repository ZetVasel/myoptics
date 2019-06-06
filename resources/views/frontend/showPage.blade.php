@extends('frontend.layout')

@section('main')

	<div class="breadCrumbBg contacts">
		<div class="container">
			<div class="row">
				<ul class="breadCrumbs col-xs-12">
					<li><a href="/">Главная</a></li>
					@if( Request::segment(1) =='news' )
						<li><a href="/news">Новости</a></li>
						<li><a href="/news/{{ $page->slug }}">{{ $page->name }}</a></li>
					@elseif( Request::segment(1) =='brand' )
						<li> <a href="/brand/{{ $page->slug }}">{{ $page->name }}</a></li>
					@else
						<li><a href="/{{ $page->slug }}">{{ $page->name }}</a></li>
					@endif

				</ul>
			</div>
		</div>
	</div>

	<div class="container container-body">
		<div style="margin-top: 30px;margin-bottom: 30px;" class="col-xs-12">
			{!! $page->body !!}
		</div>
	</div>

@endsection

@section('styles')
	<link rel="stylesheet" href="/frontend/css/contacts.css">
@endsection

