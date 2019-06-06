<div @if(!empty($page->image)) style="background: url('/uploads/category/{{ $page->image }}');background-repeat: no-repeat;background-position: left;"  @endif  class="breadCrumbBg lenses">
	<div class="container">
		<div class="row">
			<ul class="breadCrumbs col-xs-12">
				<li><a href="/">Главная</a></li>
				@foreach( $breadcrumbs as $par )
					@if( isset($par) )
						<li><a href="/category/{{ $par->slug }}">{{ $par->name }}</a></li>
					@endif
				@endforeach
				@if( Request::segment(1) == 'product' && isset($page) )
					<li><a href="{{ $page->slug }}">{{ $page->name }}</a></li>
				@endif
			</ul>
			<div class="title col-xs-12">
				{{ $page->name }}
			</div>
		</div>
	</div>
</div>