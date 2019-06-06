@extends('admin.layout')

@section('main')


	<h1>
		<a href="/master/products/edit/{{ $post->id }}" class="glyphicon glyphicon-circle-arrow-left"></a>
		
		{{ $title }}
		<span class="label label-default">
			{{ count($post->imgs) }}
			{{ Lang::choice('фотография|фотографии|фотографий', count($post->imgs), array(), 'ru') }}
		</span>
	</h1>
	
	
	{!! Form::open(array( 'id' => 'upload_form', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ) ) !!}

	<div class="form-group">
		<div class="col-sm-8">
			{!! Form::file('image[]', array('class' => 'filestyle', 'data-value'=> '', 'data-buttonText' => 'Выбрать файлы', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false' , 'multiple' => 'true') ) !!}
		</div>
		<!-- <span class="help-block col-sm-12">Оптимальный размер изображений 600 x 640</span> -->
	</div>

	<div class="form-group" >
		<div class="col-sm-10">
		  <button type="submit" class="btn btn-success">Загрузить</button>
		</div>
	</div>
	
	{!! Form::close() !!}
	
			@if( $post->imgs != '' )

				{!! Form::open(['id'=>'list' ]) !!}

					<div class="row">
						@foreach ( $post->imgs as $key => $photo )
						  <div class="col-sm-3 col-md-2">
							<div class="thumbnail">
							 <img style="width: 100%;" alt="photo" src="/uploads/products/lg/{{{ $photo }}}">
							  <div class="caption">
									<div class="btn-group">
										<button type="button" class="btn btn-default"><input style="margin: 0;" name="check[]" value="{{ $key }}" type="checkbox"></button>
										<div data-id="{{ $post->id }}" data-key="{{$key}}" data-mimg="{{$post->main_img}}" style="float:left;">
											<a title="Основное изображение" style="border-radius: 0;" class="main_img btn @if( $post->main_img == $key )btn-success visible @else btn-default @endif">
												<span class="glyphicon glyphicon-eye-open"></span>
											</a>
										</div>
										<button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $key }}"><span class="glyphicon glyphicon-remove"></span></button>
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


			@else
				<div class="alert alert-warning" role="alert">
					Нет изображений
				</div>
			@endif
@stop

@section('scripts')

<script src="/dashboard/js/imagesloaded.pkgd.min.js"></script>

<script>
	$(function() {

		// удаление записи
		$('.delete').click( function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', false);
			$(this).closest(".btn-group").find('input[type="checkbox"][name*="check"]').prop('checked', true);
			$(this).closest("form#list").find('select[name="action"] option[value=delete]').prop('selected', true);
			$(this).closest("form#list").submit();
		});
		$('.main_img').click(function(){
			var img_num = $(this).parent().data('key');
			var product_id = $(this).parent().data('id');
			var mainimg = $(this).parent().data('mimg');

			var icon = $(this);

			$('.main_img').each(function(){
				$(this).removeClass('btn-success visible');
				$(this).addClass('btn-default');
			});

			icon.addClass('btn-success visible');

			$.post( '/master/products', { _token: '{{ Session::token() }}', id: product_id, main_img: img_num });
			return false;
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
	})
</script>

@endsection