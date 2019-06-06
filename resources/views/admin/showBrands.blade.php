@extends('admin.layout')

@section('main')

	<h1>
		<a href="/master" class="glyphicon glyphicon-circle-arrow-left"></a>
		<a href="/master/brands/add" class="glyphicon glyphicon-plus-sign"></a>{{ $title }} <span class="label label-default">{{ $brands_count }}</span>
	</h1>

	@if(Session::has('message'))
		<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
	@endif

	@if( count($brands)>0 )
	<div class="row prod_sorting">
		<form class="form-inline" method="GET" id="sorting">
			<div class="form-group col-sm-3">
				{!! Form::text('search', Input::get('search'), ['class'=>'form-control', 'id' => 'search_product', 'placeholder'=>'Название бренда']) !!}
			</div>
			<div class="col-sm-1 margin-t-24">
				{!! Form::submit('Найти', ['class'=> 'btn btn-primary']) !!}
			</div>
		</form>
	</div>		
		{!! Form::open() !!}
			<table class="table">
				<tr>
					<th style="width: 50%;">Название</th>
					<th>Ссылка</th>
					<th style="text-align: right;">Управление</th>
				</tr>
				@foreach ($brands as $post)
				<tr>
					<td style="vertical-align: middle">
						<input name="check[]" value="{{ $post->id }}" type="checkbox"><a class="black_link" href="/master/brands/edit/{{ $post->id }}">{{{ $post->name }}}</a><br>
						<small style="padding-left: 22px;">{{ $post->created_at }}</small>
					</td>
					<td>{{ $post->slug }}</td>
					<td style="text-align: right;">
						<div class="btn-group">
							<a title="Редактировать" href="/master/brands/edit/{{ $post->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
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
		{!! $brands->render() !!}

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
		$("form.main_f").submit(function() {
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
