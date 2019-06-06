@extends('admin.layout')

@section('main')

	<h1>
		<a href="/master/gallery-categories" class="glyphicon glyphicon-circle-arrow-left"></a>
		
		{{ $title }}
		<span class="label label-default">
			{{ count($slides) }}
			{{ Lang::choice('слайдер|слайдера|слайдеров', count($slides), array(), 'ru') }}
		</span>
	</h1>

	{!! Form::open(array( 'id' => 'upload_form', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ) ) !!}

		<div class="form-group">
			{!! Form::label('img', 'Изображение', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::file('img', array('class' => 'filestyle', 'data-value'=> '', 'data-buttonText' => 'Выберите файлы', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false' , 'multiple' => 'true') ) !!}
			</div>
			<span class="help-block col-sm-offset-2">Оптимальный размер изображения 1140 x 368</span>
		</div>

		<div class="form-group" >
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-success">Загрузить</button>
			</div>
		</div>
	
	{!! Form::close() !!}
	
			@if( count($slides) > 0 )
			
				{!! Form::open(['id'=>'list' ]) !!}
					<div class="row">
					@foreach ( $slides as $slide )
					  <div class="col-sm-6 col-md-2">
						<div class="thumbnail">
						 <img style="width: 100%;" alt="photo" src="/uploads/gallery/{{{ $slide->img }}}">
						  <div class="caption">
								<div class="btn-group">
									<a class="btn btn-default"><input style="margin: 0;" name="check[]" value="{{ $slide->id }}" type="checkbox"></a>
									<a title="Редактировать" href="/master/slider/edit/{{ $slide->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
									<a title="Удалить" class="delete btn btn-danger" data-id="{{ $slide->id }}"><span class="glyphicon glyphicon-remove"></span></a>
								</div>
							</div>
						</div>
					  </div>
					@endforeach
					</div>

					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
						  <option value="delete">Удалить</option>
						</select>
						
						<button type="submit" style="margin-left: 20px;" class="btn btn-success">Применить</button>
					</div>

				{!! Form::close() !!}

				<!-- navigation //-->
				{!! $slides->render() !!}
			
			@else
				<div class="alert alert-warning" role="alert">
					Слайды отсутствуют
				</div>
			@endif

@stop
@section('scripts')

<script src="/dashboard/js/masonry.pkgd.min.js"></script>
<script src="/dashboard/js/imagesloaded.pkgd.min.js"></script>

<script>
	 $(function() {

		// masonry
		var $container = $('.row');
		$container.imagesLoaded( function() {
		  $container.masonry({
					//"isFitWidth": true,
					itemSelector: '.col-sm-6'
			});
		});

		// удаление записи
		$('.delete').click( function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', false);
			$(this).closest(".btn-group").find('input[type="checkbox"][name*="check"]').prop('checked', true);
			$(this).closest("form#list").find('select[name="action"] option[value=delete]').prop('selected', true);
			$(this).closest("form#list").submit();
		})

		// удаление записей
		$("form#list").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;
		});

		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});

	});
</script>
@stop