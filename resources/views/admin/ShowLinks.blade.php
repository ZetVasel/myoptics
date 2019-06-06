@extends('admin.layout')

@section('main')

	<h1>
		
	<a href="/master" class="glyphicon glyphicon-circle-arrow-left"></a>	
	<!-- <a href="/master/links/add" class="glyphicon glyphicon-plus-sign"></a> -->
		Линки<span class="label label-default">{{ count( $all_remotes ) }} {{ Lang::choice('Ссылка|ссылки|ссылок', count( $all_remotes ) , array(), 'ru') }} </span></h1>

		{!! Form::open() !!}
			@if( $remotes_count>0 )
				<table class="table">
					<tr>
						<th style="width: 20%;">Название</th>
						<th style="width: 40%">Ссылка</th>
						<!-- <th style="width: 10%">Иконка</th> -->
						<th style="width: 20%">Тип</th>
						<th style="text-align: right;">Управление</th>
					</tr>
					@foreach ($all_remotes as $post)
					<tr>
						<td>
							<input name="check[]" value="{{ $post->id }}" type="checkbox">
							<div class="black_link" style="display: inline-block">{{{ $post->name }}}</div>
								
						</td>
						<td>
							{{{ $post->link }}}
						</td>
						<!-- <td>
							<div class="links-img-div">
								<img alt="#" src="/uploads/ico/{{ $post->img }}">
							</div>		
						</td> -->
						<td>
							{{{ $post->type }}}
						</td>
						<td style="text-align: right;">
							<div class="btn-group">
								<a title="Редактировать" href="/master/links/edit/{{ $post->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
				
								<!-- <button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $post->id }}"><span class="glyphicon glyphicon-remove"></span></button> -->
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
		{!! Form::close() !!}

@endsection
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