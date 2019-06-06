@extends('admin.layout')

@section('main')

	<h1>
		<a href="/master" class="glyphicon glyphicon-circle-arrow-left"></a>
		
		{{ $title }}
		<span class="label label-default">
			{{ $slides_count }}
			{{ Lang::choice('слайд|слайда|слайдов', $slides_count, array(), 'ru') }}
		</span>
	</h1>

	{!! Form::open(array( 'id' => 'upload_form', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ) ) !!}

		<div class="form-group">
			{!! Form::label('image', 'Изображение', array('class'=>'col-sm-2 control-label') ) !!}
			<div class="col-sm-10">
				{!! Form::file('image', array('class' => 'filestyle', 'data-value'=> '', 'data-buttonText' => 'Выберите файлы', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false' , 'multiple' => 'true') ) !!}
				<span>Рекомендуемые размеры 375 x 230px</span>
			</div>
		</div>


	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Название</label></div>
		<div class="col-sm-10">
			{!! Form::text('title',  '', array('class'=>'form-control') ) !!}
		</div>
	</div>



	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Текст</label></div>
		<div class="col-sm-10">
			{!! Form::textarea('text',  '', array('class'=>'form-control') ) !!}
		</div>
	</div>


	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Текст ссылки(если оставить пустым будет "Подробнее")</label></div>
		<div class="col-sm-10">
			{!! Form::text('textButton',  '', array('class'=>'form-control') ) !!}
		</div>
	</div>


	<div class="form-group">
		<div class="col-sm-2 control-label" style="padding-top:0!important;"><label style="margin-right:10px;">Адрес ссылки</label></div>
		<div class="col-sm-10">
			{!! Form::text('link',  '', array('class'=>'form-control') ) !!}
		</div>
	</div>


		<div class="form-group" >
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-success">Загрузить</button>
			</div>
		</div>
	
	{!! Form::close() !!}
	
			@if( $slides_count > 0 )
			
				{!! Form::open(['id'=>'list', 'action' => 'Admin\AdminSliderController@postDelete' ]) !!}
					<div class="row">
					@foreach ( $slides as $slide )
					  <div class="col-sm-6 col-md-2">
						<div class="thumbnail">
						{!! Form::hidden('title', $slide->title) !!}
						{!! Form::hidden('text', $slide->text) !!}
						{!! Form::hidden('slide_id', $slide->id) !!}
						{!! Form::hidden('link', $slide->link) !!}

						 <img style="width: 100%;" alt="photo" src="/uploads/slides/{{{ $slide->image }}}" data-toggle="modal" data-target="#exampleModal">
						  <div class="caption">
								<div class="btn-group">
									<button type="button" class="btn btn-default"><input style="margin: 0;" name="check[]" value="{{ $slide->id }}" type="checkbox"></button>
									<button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $slide->id }}"><span class="glyphicon glyphicon-remove"></span></button>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Ссылка</h4>
      </div>
      <div class="modal-body">
        <form id="slide-text" >
        {!! csrf_field() !!}
        {!! Form::hidden('sl_id', '') !!}
          <div class="form-group">
            <label for="title" class="control-label">Заголовок:</label>
            <input type="text" class="form-control" name="title" id="title">
          </div>        
          <div class="form-group">
            <label for="text" class="control-label">Текст:</label>
            <input type="text" class="form-control" name="text" id="text">
          </div>        
          <div class="form-group">
            <label for="text" class="control-label">Ссылка:</label>
            <input type="text" class="form-control" name="link" id="link">
          </div>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
          <input type="submit" value="Сохранить" placeholder="" class="btn btn-primary">
        </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>


@stop
@section('scripts')

<script src="/dashboard/js/masonry.pkgd.min.js"></script>
<script src="/dashboard/js/imagesloaded.pkgd.min.js"></script>

<script>

	// Модальное окно

	 $(function() {

		$('#exampleModal').on('show.bs.modal', function (event) {
		  var cur_slide = $(event.relatedTarget);
		  var modal = $(this);
		  modal.find('#title').val( cur_slide.siblings('input[type="hidden"][name*="title"]').val() );
		  modal.find('#text').val( cur_slide.siblings('input[type="hidden"][name*="text"]').val() );
		  modal.find('#link').val( cur_slide.siblings('input[type="hidden"][name*="link"]').val() );
		  modal.find('input[type="hidden"][name*="sl_id"]').val( cur_slide.siblings('input[type="hidden"][name*="slide_id"]').val() );

		});
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
		});

		// удаление записей
		$("form#list").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;
		});

		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});



		$('#slide-text').on('submit', function(){
		var modal = $(this).serialize();
		$.post('/master/slide_text', modal, function(data){
			location.reload();
		});
		$('#exampleModal').modal('hide');
			return false;
		});

	});
</script>
@stop