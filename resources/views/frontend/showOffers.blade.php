@extends('frontend.layout')

@section('main')
<div class="breadcrumbs wrapper" style="font-size: 14px">
    <a href="/">Главная</a>
	<span> / </span> <a href="/{{ $page->slug }}">{{ $page->name }}</a>

</div>
	<div class="h1">{{ $page->name }}</div>
	@if( count($offers) )
		@foreach( $offers as $n )
			<div class="offers_block">
				 <!-- <span style="float:right">{{ $n->created_at->format('d.m.Y') }}</span> -->
				<div class="img_block"><a href="/offers/{{ $n->slug }}"><img src="uploads/offers/{{ $n->image }}" alt="{{ $n->name }}"></a></div>
				<div class="title"><a href="/offers/{{ $n->slug }}">{{ $n->name }}</a></div>
			</div>
		@endforeach
	@else
		<p class="empty_query">На данный момент новости отсутствуют</p>
	@endif
@endsection

@section('scripts')


@endsection