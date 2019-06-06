@extends('admin.layout')

@section('main')
	<h1>
		<a href="/master" class="glyphicon glyphicon-circle-arrow-left"></a>	
		<a href="/master/offers/add" class="glyphicon glyphicon-plus-sign"></a>
		{{ $title }}<span class="label label-default">{{ count( $offers ) }} </span>
	</h1>

		{!! Form::open() !!}
			@if( count($offers) > 0 )
				<table class="table">
					<tr>
						<th style="width: 20%;">Название</th>
						<th style="text-align: right;">Управление</th>
					</tr>
					@foreach ($offers as $post)
					<tr>
						<td>
							<input name="check[]" value="{{ $post->id }}" type="checkbox">
							<a href="/master/offers/edit/{{ $post->id }}"> <div class="black_link" style="display: inline-block">{{ $post->name }}</div> </a>
								
						</td>
						<td style="text-align: right;">
							<div class="btn-group">
							@if(Session::has('ok'))
								<a href="#" class="btn ok-label"><span class="label label-success">{{ Session::get('ok') }}</span></a>
							@endif
								<a title="Продукты"  href="/master/offers/products/{{ $post->id }}"  class="btn btn-info"><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;<span class="badge">{{count($post->offerproducts)}}</span></a>
								<a title="Редактировать" href="/master/offers/edit/{{ $post->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>	
								<button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $post->id }}"><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
					</tr>
					@endforeach
				</table>

				
			@else
				<div class="alert alert-warning" role="alert">
				 Нет записей
				</div>
			@endif


			<div class="select_form">
				<label id="check_all" class="link">Выбрать все</label>
				<select name="action" class="form-control">
				  <option value="delete">Удалить</option>
				</select>
				<button type="submit" style="margin-left: 20px;" class="btn btn-success">Применить</button>
			</div>			
		{!! Form::close() !!}

@endsection
@section('scripts')
	<script>
	 $(function() {
		setTimeout(function () {
			$('.ok-label').fadeOut(400)
		}, 1500); // время в мс

		// удаление записи
		$('.delete').click( function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', false);
			$(this).closest("tr").find('input[type="checkbox"][name*="check"]').prop('checked', true);
			$(this).closest("form").find('select[name="action"] option[value=delete]').prop('selected', true);
			$(this).closest("form").submit();
		})

		// удаление записей
		$("form").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;
		});
		
		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});
	})
	</script>
@endsection