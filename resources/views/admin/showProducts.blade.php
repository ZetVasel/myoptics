@extends('admin.layout')

@section('main')

	<h1>
		<a href="/master/products_management" class="glyphicon glyphicon-circle-arrow-left"></a>
		<a href="/master/products/add" class="glyphicon glyphicon-plus-sign"></a>{{ $title }}
	</h1>

	@if(Session::has('message'))
		<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
	@endif

	<div class="row prod_sorting">
		<form class="form-inline" method="GET" id="sorting">
			<div class="form-group col-sm-3" >
				<label>Продукт</label>
				{!! Form::select('product', $products_search, Input::get('product'), array( 'class'=>'form-control chosen-select', 'style'=>'max-width: 100%;')) !!}
			</div>
			<div class="form-group col-sm-3" >
				<label>Категория</label>
				{!! Form::select('category', $categories, Input::get('category'), array( 'class'=>'form-control chosen-select', 'style'=>'max-width: 100%;')) !!}
			</div>

		</form>
	</div>

	@if( count($products)>0 )
		{!! Form::open(['class' => 'main_f']) !!}
			<table class="table">
				<tr>
					<th colspan="2" width="20%">Наименование товара</th>
					<th width="10%">Код товара</th>
					<th width="10%">Цена</th>
					<!-- <th>Товар в наличии</th> -->
					<th>Дата публикации</th>
					<th>Метка</th>
					<th style="text-align: right;">Управление</th>
				</tr>

				@foreach ($products as $post)
				<tr class="@if($post->in_stock==1)success @else danger @endif">
					<td colspan="2" >
						<input name="check[]" value="{{ $post->id }}" type="checkbox">
						<a class="black_link" href="/master/products/edit/{{ $post->id }}">{{{ $post->name }}}</a>
					</td>
					<td>{{ $post->code }}</td>
					<td>{{ $post->price }}</td>
					<!-- <td>
						<div data-id="{{ $post->id }}">
							<a title="Наличие товара" class="in_stock btn @if( $post->in_stock )btn-success visible @else btn-danger @endif">
								<span class="glyphicon @if( $post->in_stock ) glyphicon-ok @else glyphicon-remove @endif"></span>
							</a>
						</div>
					</td> -->
					<td>{{ $post->created_at->format('d.m.Y') }}</td>
					<td>
						<div class="form-group" style="margin-right:0;" data-id="{{ $post->id }}">
							<a title="Показывать как новинку" class="new btn @if( $post->new )btn-success visible @else btn-default @endif"><span class="glyphicon glyphicon-asterisk"></span></a>
							<a title="Показывать как скидку" class="hit btn @if( $post->discount ) btn-danger visible @else btn-default @endif"><span class="glyphicon glyphicon-star"></span></a>
							<a title="Показывать как распродажа" class="promo btn @if( $post->sale )btn-danger visible @else btn-default @endif"><span class="glyphicon glyphicon-flag"></span></a>
						</div>
					</td>
					<td style="text-align: right;">
						<div class="btn-group">
							<a title="Редактировать" href="/master/products/edit/{{ $post->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
							<a title="Открыть в новом окне" target="_blank" href="/product/{{ $post->slug }}" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span></a>
							<button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $post->id }}"><span class="glyphicon glyphicon-remove"></span></button>
						</div>
					</td>
				</tr>
				@endforeach
			</table>

			<div class="select_form">
				<label id="check_all" class="link">Выбрать все</label>
				<select name="action" class="form-control">
				  <option value="delete">Удалить</option>
				</select>
				<button type="submit" style="margin-left: 20px;" class="btn btn-success">Применить</button>
			</div>
		{!! Form::close() !!}

		<!-- navigation //-->
		{!! $products->appends(Input::all())->render() !!}

	@else
		<div class="alert alert-warning" role="alert">
		 Нет записей
		</div>
	@endif
	<style>
		.green{

		}
	</style>
@stop

@section('styles')
	<link rel="stylesheet" href="/dashboard/js/chosen/chosen.min.css">
@endsection

@section('scripts')
<script src="/dashboard/js/chosen/chosen.jquery.min.js"></script>
<script>
	
	$(document).ready(function(){
		$('.chosen-select').chosen();
	});
	 $(function() {
	 	

		// удаление записи
		$('.delete').click( function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', false);
			$(this).closest("tr").find('input[type="checkbox"][name*="check"]').prop('checked', true);
			$(this).closest("form").find('select[name="action"] option[value=delete]').prop('selected', true);
			$(this).closest("form").submit();
		})

		// удаление записей
		$("form.main_f").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;
		});

		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});

		$('#sorting select').change(function(){
			$('#sorting').submit();
		});

		//Наличие товара
		$('.in_stock').click( function() {

			var icon = $(this);
			var state = icon.hasClass('visible') ? 0 : 1;
			if(state) {
				icon.removeClass('btn-danger');
				icon.addClass('btn-success visible');
				icon.children('span').removeClass('glyphicon-remove');
				icon.children('span').addClass('glyphicon-ok');
				icon.parent().parent().parent('tr').removeClass('danger');
				icon.parent().parent().parent('tr').addClass('success');
			} else {
				icon.removeClass('visible btn-success');
				icon.addClass('btn-danger');
				icon.children('span').removeClass('glyphicon-ok');
				icon.children('span').addClass('glyphicon-remove');
				icon.parent().parent().parent('tr').removeClass('success');
				icon.parent().parent().parent('tr').addClass('danger');
			}

			$.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), in_stock: state });
			return false;
		});
         // хит товар
         $('.hit').click( function() {

             var icon = $(this);
             var state = icon.hasClass('visible') ? 0 : 1;
             if(state) {
                 icon.removeClass('btn-default');
                 icon.addClass('btn-danger visible');
             } else {
                 icon.removeClass('visible btn-danger');
                 icon.addClass('btn-default');
             }
             $(this).parent('.form-group').find('.promo').removeClass('visible btn-danger');
             $(this).parent('.form-group').find('.promo').addClass('btn-default');

             $(this).parent('.form-group').find('.new').removeClass('visible btn-success');
             $(this).parent('.form-group').find('.new').addClass('btn-default');


             $.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), discount: state });
             return false;
         });


         //НОВЫЙ
         $('.new').click( function() {

             var icon = $(this);
             var state = icon.hasClass('visible') ? 0 : 1;
             if(state) {
                 icon.removeClass('btn-default');
                 icon.addClass('btn-success visible');
             } else {
                 icon.removeClass('visible btn-success');
                 icon.addClass('btn-default');
             }

             $(this).parent('.form-group').find('.hit').removeClass('visible btn-danger');
             $(this).parent('.form-group').find('.hit').addClass('btn-default');

             $(this).parent('.form-group').find('.promo').removeClass('visible btn-danger');
             $(this).parent('.form-group').find('.promo').addClass('btn-default');



             $.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), new: state });
             return false;
         });

         //TOP
         $('.promo').click( function() {
             var icon = $(this);
             var state = icon.hasClass('visible') ? 0 : 1;
             if(state) {
                 icon.removeClass('btn-default');
                 icon.addClass('btn-danger visible');
             } else {
                 icon.removeClass('visible btn-danger');
                 icon.addClass('btn-default');
             }


             $(this).parent('.form-group').find('.hit').removeClass('visible btn-danger');
             $(this).parent('.form-group').find('.hit').addClass('btn-default');

             $(this).parent('.form-group').find('.new').removeClass('visible btn-success');
             $(this).parent('.form-group').find('.new').addClass('btn-default');


             $.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), sale: state });
             return false;
         });

     });

</script>

@endsection