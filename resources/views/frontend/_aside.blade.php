<aside class="left-sidebar">
	@if( count($parent_cat) )
	<div class="h2">Категории</div>
	<nav>
		<ul>
		@foreach( $parent_cat as $pc )
			<li @if( Request::segment(2) == $pc->slug ) class="active" @endif><a href="/category/{{ $pc->slug }}">{{ $pc->name }}</a></li>
		@endforeach
		</ul>
	</nav>
	@endif

	<div class="h2">Фильтр товаров</div>
	@if( Input::has('filter') )
	<div class="selected-filters">
		<p>Выбранные фильтры</p>
		@foreach( $features as $f )
			@if( isset( Input::get('filter')[$f->id] ) )
				@foreach( $f->options as $key => $o )
					@if( isset( Input::get('filter')[$f->id] )  && in_array( $o->value, Input::get('filter')[$f->id] ) )
					<p><a href="#" data-id="{{ $f->id.$o->id }}" class="close"></a><span>{{ $o->value }}</span></p>
					@endif
				@endforeach
			@endif							
		@endforeach
		<div id="clear_all_filters">
			Сбросить
		</div>				
	</div>
	@endif	
	<form id="filter">
	<figure class="filter-group price">
<!-- 		<figcaption>Цена</figcaption>
		<input name="priceMin" type="text" id="priceMin" pattern="^[0-9]+$" value="{{ $cur_price['min'] }}">
		<span> &mdash; </span>
		<input name="priceMax" type="text" id="priceMax" pattern="^[0-9]+$" value="{{ $cur_price['max'] }}">
		<br><br>
		<div id="price-range" class="price_range" data-max="{{ $price_range['max']}}"  data-min="{{ $price_range['min']}}"></div> -->

			<figcaption>Цена</figcaption>					
			<div id="price-diapazon"></div>
			<input name="priceMin" type="text" id="priceMin" pattern="^[0-9]+$" value="{{ $cur_price['min'] }}">
			<span> &mdash; </span>
			<input name="priceMax" type="text" id="priceMax" pattern="^[0-9]+$" value="{{ $cur_price['max'] }}">
			<br>
			<div id="price-range" class="price_range" data-max="{{ $price_range['max']}}"  data-min="{{ $price_range['min']}}"></div>
			<div id="price_slider"></div>

	</figure>	
	@foreach( $features as $f )
		@if( count( $f->options ) )
		<figure class="filter-group">
             <figcaption><div class="h3">{{ $f->name }}</div></figcaption>
             @foreach( $f->options as $key => $o )
                <label class="lab{{ $key }}">
                    <input @if( isset( Input::get('filter')[$f->id] )  && in_array( $o->value, Input::get('filter')[$f->id] ) ) checked @endif onchange="this.form.submit()" name="filter[{{ $f->id }}][]" type="checkbox" value="{{{ $o->value }}}" id="{{ $f->id.$o->id }}">
                    <span>{{ $o->value }} <i> ({{ $o->count }})</i></span>
                </label>  
            @endforeach	                          
		</figure>
		@endif	
	@endforeach			
	</form>
</aside><!-- .left-sidebar -->	