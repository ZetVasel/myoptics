@extends('admin.layout')

@section('main')

	<h1><a href="/master/feedback_management" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}<span class="label label-default">{{ $reviews_count }} {{ Lang::choice('отзыв|отзыва|отзывов', $reviews_count, array(), 'ru') }}</span></h1>
	
			@if( count($reviews)>0 )
				{!! Form::open() !!}
					<table class="table">
						<tr>
							<th style="width: 90%;">Отзыв</th>
							<th style="text-align: right;">Управление</th>
						</tr>

						@foreach ($reviews as $post)
						<tr @if( $post->visible != 1) class="unapproved" @endif>
							<td>
								<input name="check[]" value="{{ $post->id }}" type="checkbox"> 
								Отзыв от пользователя: {{ $post->name }} к товару <a href="/product/{{ $post->prod_slug }}" target="_blank">{{ $post->prod_name }}</a>
								<p>Текст отзыва: {{ $post->text }}</p><br>
								<small>Отзыв оставлен {{{ $post->created_at }}}</small>
							</td>

							<td style="text-align: right;">
								<div class="btn-group">
									<a href="#" title="Показывать отзыв" class="enable btn @if( $post->visible )btn-success visible @else btn-default @endif"><span class="glyphicon glyphicon-ok"></span></a>
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
				{{ $reviews->render() }}
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
		});

		// удаление записей
		$("form").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;
		});

		$('.enable').click( function() {

			var icon = $(this);
			var state = icon.hasClass('visible') ? 0 : 1;

			if(state) {
				icon.removeClass('btn-default');
				icon.addClass('btn-success visible');
				$(this).closest('tr').removeClass('unapproved');
			} else {
				icon.removeClass('visible btn-success');
				icon.addClass('btn-default');
				$(this).closest('tr').addClass('unapproved');
			}

				$.post( '{{ Request::url() }}', { _token: '{{ Session::token() }}', id: $(this).closest('tr').find('input').val(), visible: state }, function(data){
					console.log(data);
				});
				return false;
		});

		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').attr('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});
	})
</script>

@endsection