@extends('admin.layout')

@section('main')

	<h1>
		<a href="/master" class="glyphicon glyphicon-circle-arrow-left"></a>
		<a href="/master/notify/add" class="glyphicon glyphicon-plus-sign"></a>{{ $title }} <span class="label label-default">{{ $notify_count }}</span>
	</h1>

	@if(Session::has('message'))
		<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
	@endif

	@if( count($notify)>0 )
		{!! Form::open() !!}
			<table class="table">
				<tr>
					<th>№</th>
					<th>Пользователь</th>
					<th>Дата напоминания</th>
					<th>Повторяемое</th>
					<th>Повторять через</th>
					<th>Напоминание создано</th>
					<th>Удаление</th>
				</tr>
				@foreach ($notify as $post)
					<?

                    $lastSend = explode(' ', $post->last_send);
                    $date = new DateTime($lastSend[0]);
                    $date->add(new DateInterval("P".$post->interval."D"));
                    $nextSendDate = $date->format('Y-m-d');
					?>


				<tr>
					<td style="vertical-align: middle">
						<input name="check[]" value="{{ $post->id }}" type="checkbox"> {{ $post->id }}<br>
					</td>

					<td style="vertical-align: middle">
						<b>{{ $post->firstName }}</b><br>
						email: <a href="mailto:{{ $post->email }}">{{ $post->email }}</a><br>
					</td>

					<td style="vertical-align: middle">
						{{ $nextSendDate }}<br>
					</td>

					<td style="vertical-align: middle">
						{{ $post->repeat == 1 ? 'Да' : 'Нет' }}<br>
					</td>

					<td style="vertical-align: middle">
						Каждые {{ $post->interval }} дней
					</td>


					<td style="vertical-align: middle">
						{{ $post->created_at }}<br>
					</td>


					<td style="vertical-align: middle">
						<div class="btn-group">
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
		{!! $notify->render() !!}

	@else
		<div class="alert alert-warning" role="alert">
		 Нет записей
		</div>
	@endif
@stop

@section('scripts')
	<script>
	 $(function() {

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
