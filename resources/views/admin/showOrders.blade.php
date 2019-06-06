@extends('admin.layout')

@section('main')

	<h1><a href="/master/orders" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}<span class="label label-default">{{ $orders_count }} {{ Lang::choice('заказ|заказа|заказов', $orders_count, array(), 'ru') }}</span></h1>


	<div class="row prod_sorting">
		<form class="form-inline" method="GET" id="sortingForm">
			<div class="form-group col-sm-3" >
				<label>ФИО</label>
				{!! Form::select('fio', $fio, Input::get('fio'), array( 'class'=>'form-control chosen-select', 'style'=>'max-width: 100%;')) !!}
			</div>
			<div class="form-group col-sm-3" >
				<label>Телефон</label>
				{!! Form::select('phone', $phones, Input::get('phone'), array( 'class'=>'form-control chosen-select', 'style'=>'max-width: 100%;')) !!}
			</div>

		</form>
	</div>

	@if( count($orders)>0 )
		{!! Form::open() !!}
			<table class="table">
				<tr>
					<th style="width: 90%;">Сообщение</th>
					<th style="text-align: right;">Управление</th>
				</tr>

				@foreach ($orders as $post)
					<tr>
						<td>
							<input name="check[]" value="{{ $post->id }}" type="checkbox"> 
							<big>Заказ оформил {{ $post->name }} на сумму {{ $post->total_cost }} грн.</big><br>
							<big>Метод доставки: {{ $post->d_name }}</big><br>
							<big>Новая почта: {{ $post->w_city }} - {{ $post->w_address }}</big><br>
							<select>
								@foreach($orders as $post)
								<option>Дополнительное описание товара: {{ $post->m }} - {{ $post->v }}</option>
									@endforeach
							</select>
							<div style="padding: 2px 0 5px 0">
								<a href="/master/orders/edit/{{ $type }}/{{ $post->id }}">Посмотреть подробную информацию</a>
							</div>
							<small>Заказ получен {{{ $post->created_at }}}</small>
						</td>
						<td style="text-align: right;">
							<div class="btn-group">
								<button type="button" class="delete btn btn-danger" data-id="{{ $post->id }}"><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
					</tr>
				@endforeach

			</table>

			<div class="select_form">
				<label id="check_all" class="link">Выбрать все</label>
				<select name="action" class="form-control">
				  <option value="delete">удалить</option>
				</select>
				<button type="submit" style="margin-left: 20px;" class="btn btn-success">Применить</button>
			</div>
		{!! Form::close() !!}

		<!-- navigation //-->
		{!! $orders->render() !!}
	@else
		<div class="alert alert-warning" role="alert">
			Нет заказов
		</div>
	@endif
@stop

@section('scripts')
	<link rel="stylesheet" href="/dashboard/js/chosen/chosen.min.css">
	<script src="/dashboard/js/chosen/chosen.jquery.min.js"></script>
	<script>

        $(document).ready(function(){
            $('.chosen-select').chosen();
        });

		 $(function() {


             $('#sortingForm select').change(function(){
                 $('#sortingForm').submit();
             });


			// удаление записи
			$('.delete').click( function() {
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest("tr").find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest("form").find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest("form").submit();
			});

			// удаление записей
			$("form").submit(function() {
			    if($(this).attr('id') != 'sortingForm'){
                    if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
                        return false;
                }
			});

			// выделить все
			$("#check_all").on( 'click', function() {
				$('input[type="checkbox"][name*="check"]').attr('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
			});
		})
	</script>
@endsection