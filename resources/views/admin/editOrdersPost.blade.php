@extends('admin.layout')

@section('main')

	<h1><a href="/master/orders/show/{{ $type }}" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1>
	<br>
	
	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
	@endif

	<table class="table table-striped">

		@if( $post->fio )
			<tr>
				<td>Заказ оформил</td>
				<td>{{ $post->fio }}</td>
			</tr>
		@endif
		<tr>
			<td>Телефон заказчика</td>
			<td>{{ $post->phone }}</td>
		</tr>
		@if( $post->email )
			<tr>
				<td>Email заказчика</td>
				<td>{{ $post->email }}</td>
			</tr>
		@endif
			<tr>
				<td>Комментарий</td>
				<td>{{ $post->comment }}</td>
			</tr>
			<tr>
				<td>Город</td>
				<td>{{ $post->city }}</td>
			</tr>
			<tr>
				<td>Адрес</td>
				<td>{{ $post->address }}</td>
			</tr>
			@if( $delivery !='' )
			<tr>
				<td>Метод доставки</td>
				<td>{{ $delivery->name }}</td>
			</tr>	
			@endif
			@if( $warehouse !='' ) <!-- np -->
				<tr>
					<td>Новая почта</td>
					<td>{{ $warehouse->cityRu }} - {{ $warehouse->addressRu }}</td>
				</tr>
			@endif
			@if( $payment !='' )
			<tr>
				<td>Метод Оплаты</td>
				<td>{{ $payment->name }}</td>
			</tr>	
			@endif
	</table>


	<h1>Заказанные товары</h1>
	<table class="table table-bordered table-striped">

		<tr>
			<td>Название товара</td>
			<td>Код товара</td>
			<td>Цена (за 1 товар)</td>
			<td>Заказанное количество</td>
			<td>Сумма (с учетом скидки)</td>
			<td>Характеристики заказа</td>
		</tr>

		@foreach( $ordered_prod as $op )
			<tr>
				<td><a href="/master/products/edit/{{ $op->prod_id }}" target="_blank">{{ $op->prod_name }}</a></td>
				<td>{{ $op->prod_code }}</td>
				<td>{{ $op->cost }} грн.</td>
				<td>{{ $op->quantity }}</td>
				<td>{{ $op->cost * $op->quantity - ($op->cost * $op->quantity * $op->discount) }} грн. (скидка {{ ($op->cost * $op->quantity * $op->discount) }} грн. ) </td>
				<td style="text-align: center"><a href="#" data-id="{{ $op->id }}" class="btn btn-primary getChar">Просмотреть</a></td>
			</tr>
		@endforeach


		<tr>
			<td colspan="6" class="text-right" style="font-size:16px;">Общая сумма заказа {{ $post->total_cost }} грн.</td>
		</tr>
	</table>

	<table class="table table-dark table-striped">
		<tr>
			<td colspan="2" class="text-center">Дополнительное описание товара</td> <!-- pd -->
		</tr>

		@foreach( $ordered_prod as $pd )
			<tr>
				<td>{{ $pd->m}}</td>
				<td>{{ $pd->v}}</td>
			</tr>
		@endforeach
	</table>



	{!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form') ) !!}

	<div class="form-group">
		{!! Form::label('status', 'Статус заказа', array('class'=>'col-sm-2 control-label') ) !!}
		<div class="col-sm-2">
			{!! Form::select('status', array('0' => 'Новый', '1' => 'Принят', '2' => 'Выполнен', '3' => 'Отменен' ), $post->status, array( 'class'=>'form-control')) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('paid', 'Оплачен', array('class'=>'col-sm-2 control-label') ) !!}
		<div class="col-sm-2">
			{!! Form::select('paid', array('0' => 'Нет', '1' => 'Да'), $post->paid, array( 'class'=>'form-control')) !!}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-success">Сохранить</button>
		</div>
	</div>

	{!! Form::close() !!}



	<!-- Modal -->
	<div class="modal fade" id="charModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Характеристики заказа</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="modal-body">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')

	<script type="text/javascript">

		$(document).on('click', '.getChar', function (e) {
            e.preventDefault();
            $.post( '/master/orders/edit', { _token: '{{ Session::token() }}', id: $(this).data('id') },
				function (data) {
                    var html = '';
                    $.each(data, function(arrayID, char) {
						html += '<div class="item"> <span class="name"><b>'+ char.name +'</b> </span> <span class="value">'+ char.value +'</span></div>';
                    });
                    $('#modal-body').html(html);
                    $('#charModal').modal('show');
            	});
        });

	</script>
@endsection