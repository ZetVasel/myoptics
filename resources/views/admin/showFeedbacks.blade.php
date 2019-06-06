@extends('admin.layout')

@section('main')

	<h1>{{ $title }}<span class="label label-default">{{ $feedbacks_count }} {{ Lang::choice('запись|записи|записей', $feedbacks_count, array(), 'ru') }}</span></h1>

	@if( count($feedbacks)>0 )
		{!! Form::open(['action' => 'Admin\AdminFeedbackController@postIndex']) !!}
			<table class="table">
				<tr>
					<th style="width: 90%;">Сообщение</th>
					<th style="text-align: right;">Управление</th>
				</tr>
				@foreach ($feedbacks as $post)
					<tr>
						<td>
							<input name="check[]" value="{{ $post->id }}" type="checkbox"> 
							<big>Получено сообщение</big><br>
							<div style="padding: 2px 0 5px 0">
								<a href="#post_{{$post->id}}" class="inlinepopup">Посмотреть подробную информацию</a>
								<div id="post_{{$post->id}}" class="small_dialog mfp-hide zoom-anim-dialog">
									<h1>Сообщение от {{{ $post->created_at }}}</h1>
									<table>
										<tr>
											<td><b>Получено сообщение от:</b></td>
											<td>{{{ $post->firstName }}}</td>
										</tr>

										@if($post->email != '')
										<tr>
											<td><b>Контактный E-mail:</b></td>
											<td>{{{ $post->email }}}</td>
										</tr>
										@endif
										@if($post->comment != '')
										<tr>
											<td><b>Текст:</b></td>
											<td>{{{ $post->comment }}}</td>
										</tr>
										@endif

									</table>

									@if(count($post->files) > 0)
										<h4>Файлы:</h4>
										@foreach($post->files as $item)
											<div class="file">
												<a href="/uploads/serviceFiles/{{ $item->name }}">{{ $item->name }}</a>
											</div>
										@endforeach
									@endif

								</div>



							</div>


							<small>Сообщение получено {{{ $post->created_at }}}</small>
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
		{!! $feedbacks->render() !!}

	@else
		<div class="alert alert-warning" role="alert">
			Нет записей
		</div>
	@endif

@stop

@section('scripts')

<script>
	$(function() {

	      $('.inlinepopup').magnificPopup({
	          type: 'inline',
	          closeBtnInside: true,
	          preloader: false,
	          midClick: true,
	          removalDelay: 200,
	          mainClass: 'my-mfp-slide-bottom',
			  callbacks: {
			    beforeClose: function() {
			        this.content.addClass('animated fadeOutRightBig');
			    }, 
			    close: function() {
			        this.content.removeClass('animated fadeOutRightBig'); 
			    }
			}
        });

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
	});
</script>

@endsection