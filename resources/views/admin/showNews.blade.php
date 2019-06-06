@extends('admin.layout')

@section('main')

	<h1>
		<a href="/master" class="glyphicon glyphicon-circle-arrow-left"></a>
		<a href="/master/news/add" class="glyphicon glyphicon-plus-sign"></a>{{ $title }} <span class="label label-default">{{ $news_count }}</span>
	</h1>

	@if( count($news)>0 )
		{!! Form::open() !!}
			<table class="table">
				<tr>
					<th style="width: 70%;">Название</th>

					<th style="text-align: right;">Управление</th>
				</tr>
				@foreach ($news as $post)
				<tr>
					<td style="vertical-align: middle">
						<input name="check[]" value="{{ $post->id }}" type="checkbox"><a class="black_link" href="/master/news/edit/{{ $post->id }}">{{{ $post->name }}}</a><br>
						<small style="padding-left: 22px;">{{ $post->created_at }}</small>
					</td>
					<td style="text-align: right;">
						<div class="btn-group">
							<a title="Редактировать" href="/master/news/edit/{{ $post->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
							<a title="Открыть в новом окне" target="_blank" href="/news/{{ $post->slug }}" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span></a>
							<button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $post->id }}"><span class="glyphicon glyphicon-remove"></span></button>
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
		{!! $news->render() !!}
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

		});
	</script>

@endsection