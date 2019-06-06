@extends('frontend.layout')

@section('main')




<div class="comper">
<div class="h1" style="padding-bottom: 20px;margin-bottom: 0px;border-bottom: 1px solid #333333;"><div>{{ $page->name }}</div></div>
	<div class="add_comper">
		<span>В сравниении <span class="bold">{{ count($products) }}</span> товаров</span>
		<a href="{{ Input::get('prev') }}"><div class="add"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить ещё</div></a>
	</div>
		@foreach( $category as $cat )
			<div class="comper_cat">
				<div class="img_cat" style="height:162px; background: #fff">
					<a href="/compare/{{ $cat->slug }}"></a>
					@if( $cat->image !='' )
						<img style="height:100%"  src="/uploads/categories/{{ $cat->image }}" alt="#">
					@else
						<img src="/frontend/img/no_image.jpg" alt="#">
					@endif
				</div>
				<div class="title_cat">
					{{ $cat->name }} ({{$cat->count}})
				</div>
				<div class="bat_comper"><a href="/compare/{{ $cat->slug }}?prev={{ Input::get('prev') }}">Сравнить эти товары</a></div>
			</div>
		@endforeach
	@if( $page_false == 0 ) 

		<div class="table_compar">
					<table class="first_table">
						<tr>
							<td></td>
							@foreach ($products as $chunk)
							    <td>Удалить <i class="fa fa-close close" aria-hidden="true" data-product-id="{{ $chunk->id }}"></td>
							@endforeach 
						</tr>
					
						<tr>
							<td> Вид товара </td>
							@foreach($products as $image)
								<td><img src="/uploads/products/sm/{{ $image->image }}" alt="#"></td>
							@endforeach
						</tr>
						<tr>
							<td> Название </td>
							@foreach($products as $name)
								<td>{{ $name->name }}</td>
							@endforeach
					
						</tr>
						
						<tr>
							<td> Цена </td>
							@foreach($products as $prod)
								<td>{{ $prod->price - ( $prod->price * $prod->p_discount/100 ) }} грн</td>
							@endforeach
						</tr>
						
						<tr>
							<td> Код </td>
							@foreach($products as $name)
								<td>{{ $name->code }}</td>
							@endforeach
						</tr>

					</table>
					<div class="title_table">Основные характеристики</div>
					<table class="osn_char">
						@foreach( $features as $feat )
							<tr>
								<!-- название фильтра -->
								<td>{{ $feat->name }}: </td>
								<!-- значение фильтра -->
								@foreach($products as $prod)
								<td class={{ $prod->name }}>
									<?php $test = $feat->options->where('product_id', $prod->id)->all();  ?>
									@if( $test )
										@foreach($test as $prod)
											{{ $prod->value }}<br>
										@endforeach
									@endif
									<!-- @foreach( $feat->options as $option )
										@if( $option->product_id == $prod->id )
											{{ $option->value }}
										@endif
									@endforeach -->
								</td>
								@endforeach
							</tr>
						@endforeach
					</table>
					<div class="add_comper">
						<!-- <span>В сравниении <span class="bold">{{ count($products) }}</span> товаров</span> -->
						<a href="{{ Input::get('prev') }}"><div class="add"><i class="fa fa-plus-circle" style="color:#fff" aria-hidden="true"></i> Добавить ещё</div></a>
					</div>
				</div>
	@else
	
	@endif
</div>




@endsection

@section('styles')
<link type="text/css" href="/frontend/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />
@endsection
@section('scripts')
<script type="text/javascript" src="/frontend/js/jquery.jscrollpane.min.js"></script>
	<script>
		$(function()
			{
				$('.table_compar').jScrollPane();
			});
		(function(){
			'use strict';

			// делает все колонки одинаковой высоты
			var maxHeight = 0;
			$.each( $('.compare_wr .comp_item'), function(){
				if( $(this).height() > maxHeight )
					maxHeight = $(this).height();
			});
			$('.compare_wr .comp_item').height( maxHeight );


			// удалить из сравнения
			$('.close').on('click', function(){
				var clicked = $(this);
				$.post('/remove-from-compare', {
					_token: '{{ csrf_token() }}',
					product_id: $(this).data('productId')
				}, function(){
					clicked.parent().remove();
					location.reload();
				});
				return false;
			});
		})();
	</script>

@endsection