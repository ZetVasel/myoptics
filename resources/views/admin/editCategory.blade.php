@extends('admin.layout')

@section('main')

	<h1><a href="/master/categories" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1><br>

	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
	@endif

	@include('errors.formErrors')

	{!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true) ) !!}

	<ul class="nav nav-tabs">
	  <li class="active"><a href="#tab_a" data-toggle="tab">Запись</a></li>
	  <li><a href="#tab_b" data-toggle="tab">Параметры страницы</a></li>
	</ul>
	<div class="tab-content">
			<div class="tab-pane active" id="tab_a">



				<div class="form-group" data-id="{{ $post->id }}">
					<label for="show_in_block_1" class="col-sm-2 control-label">Показать в Блоке 1:</label>
					<div class="col-sm-2">
						<a title="Показать в Блоке 1" data-block="show_in_block_1" data-id="{{$post->id}}" data-value="{{ $post->show_block_1 }}" class="show_in_block btn @if( $post->show_block_1 == 1 ) btn-success visible @else btn-danger @endif ">
							<span class="glyphicon  @if( $post->show_block_1 == 1 ) glyphicon-ok @else glyphicon-remove @endif"></span>
						</a>
					</div>
				</div>
				<div class="form-group" data-id="{{ $post->id }}">
					<label for="show_in_block_2" class="col-sm-2 control-label">Показать в Блоке 2:</label>
					<div class="col-sm-2">
						<a title="Показать в Блоке 2" data-block="show_in_block_2" data-id="{{$post->id}}" data-value="{{ $post->show_block_2 }}" class="show_in_block btn @if( $post->show_block_2 == 1 ) btn-success visible @else btn-danger @endif ">
							<span class="glyphicon  @if( $post->show_block_2 == 1 ) glyphicon-ok @else glyphicon-remove @endif"></span>
						</a>
					</div>
				</div>
				<div class="form-group" data-id="{{ $post->id }}">
					<label for="show_in_block_3" class="col-sm-2 control-label">Показать в Блоке 3:</label>
					<div class="col-sm-2">
						<a title="Показать в Блоке 3" data-block="show_in_block_3" data-id="{{$post->id}}" data-value="{{ $post->show_block_3 }}" class="show_in_block btn @if( $post->show_block_3 == 1 ) btn-success visible @else btn-danger @endif ">
							<span class="glyphicon  @if( $post->show_block_3 == 1 ) glyphicon-ok @else glyphicon-remove @endif"></span>
						</a>
					</div>
				</div>

				<div class="form-group">
					<label for="gradient" class="col-sm-2 control-label">Градиент на главной</label>
					<div class="col-sm-10">
						{!! Form::select('gradient', ['' => '-', 'blue' => 'Синий', 'green' => 'Зеленый', 'yellow' => 'Оранжевый'], $post->gradient, array( 'class'=>'form-control')) !!}
					</div>
				</div>

				<div class="form-group">&nbsp;</div>

				<div class="form-group">
					<label for="inputName" class="col-sm-2 control-label">Название</label>
					<div class="col-sm-10">
						<div class="input-group input-group-lg">
						  <span class="input-group-addon"></span>
						  <input type="text" class="form-control" id="inputName" name="name" value="{{ $post->name }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="parent_id" class="col-sm-2 control-label">Родительская категория</label>
					<div class="col-sm-10">
						{!! Form::select('parent_id', $tree, $parent_id, array( 'class'=>'form-control')) !!}
					</div>
				</div>









					<div class="form-group">
						<label for="image" class="col-sm-2 control-label">Изображение категории</label>
						<div class="col-sm-10">
							{!! Form::file('image', array('class' => 'filestyle', 'data-value'=> $post->image, 'data-buttonText' => 'Выбрать файл', 'data-buttonName' => 'btn-primary', 'data-icon' => 'false' ) ) !!}
							<span>Рекомендуемые размеры 263 x 325px</span>
						</div>
					</div>

					
					@if(!empty($post->image))
<!-- 						<div class="col-sm-2"></div>
						<img style="margin: 0 0 25px;" src="/uploads/categories/{{$post->image}}" alt ="Изображение категории"> -->
					@else
						
					@endif

				<div class="form-group">
					<label for="textBody" class="col-sm-2 control-label">Полное описание</label>
					<div class="col-sm-10">
					  <textarea class="form-control editor" id="textBody" name="body">{{ $post->body }}</textarea>
					</div>
				</div>

			</div>
			<div class="tab-pane" id="tab_b">

				<div class="form-group">&nbsp;</div>

				  <div class="form-group">
					<label for="inputSlug" class="col-sm-2 control-label">Адрес</label>
					<div class="col-sm-10">

					<div class="input-group">
					 <span class="input-group-addon">&nbsp;/&nbsp;</span>
					  <input type="text" class="form-control" id="inputSlug" name="slug" value="{{ $post->slug }}">
					 </div>
					</div>
				  </div>
				  
				  <div class="form-group">
					<label for="inputMetaTitle" class="col-sm-2 control-label">Заголовок</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputMetaTitle" name="meta_title" value="{{ $post->meta_title }}">
					</div>
				  </div>
				  
				  <div class="form-group">
					<label for="inputMetaKeys" class="col-sm-2 control-label">Ключевые слова</label>
					<div class="col-sm-10">
					  <input type="text" class="form-control" id="inputMetaKeys" name="meta_keywords" value="{{ $post->meta_keywords }}">
					</div>
				  </div>
				  
				  <div class="form-group">
					<label for="textMetaDescr" class="col-sm-2 control-label">Описание</label>
					<div class="col-sm-10">
					  <textarea class="form-control" id="textMetaDescr" name="meta_description">{{ $post->meta_description }}</textarea>
					</div>
				  </div>
			</div>

	</div><!-- tab content -->
	  
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-success">Сохранить</button>
		</div>
	  </div>


	{!! Form::close() !!}

@include('admin.tinymce_init')

@stop

@section('scripts')

	<script>
	$(document).ready(function(){
		meta_title_touched = false;
		url_touched = false;

		$('input[name="slug"]').change(function() { url_touched = true; });
		$('input[name="name"]').keyup(function() { 
			if(!url_touched)
				$('input[name="slug"]').val(generate_url()); 

			if(!meta_title_touched)
				$('input[name="meta_title"]').val( $('input[name="name"]').val() );
		});

		$('input[name="meta_title"]').change(function() { meta_title_touched = true; });


		function generate_url()
		{
			url = $('input[name="name"]').val();
			url = url.replace(/[\s]+/gi, '-');
			url = translit(url);
			url = url.replace(/[^0-9a-z_\-]+/gi, '').toLowerCase();	
			return url;
		}

		function translit(str)
		{
			var ru=("А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я").split("-")   
			var en=("A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch-'-'-Y-y-'-'-E-e-YU-yu-YA-ya").split("-")   
		 	var res = '';
			for(var i=0, l=str.length; i<l; i++)
			{ 
				var s = str.charAt(i), n = ru.indexOf(s); 
				if(n >= 0) { res += en[n]; } 
				else { res += s; } 
		    } 
		    return res;
		}


        $('.show_in_block').click( function() {
            var icon = $(this);
            var state = icon.hasClass('visible') ? 0 : 1;
            var cat_id = icon.data('id');
            var block = icon.data('block');
            if(state) {
                icon.removeClass('btn-danger');
                icon.addClass('btn-success visible');
                icon.children('span').removeClass('glyphicon-remove');
                icon.children('span').addClass('glyphicon-ok');
            } else {
                icon.removeClass('visible btn-success');
                icon.addClass('btn-danger');
                icon.children('span').removeClass('glyphicon-ok');
                icon.children('span').addClass('glyphicon-remove');
            }

            $.post( '/master/categories', { _token: '{{ Session::token() }}', id: cat_id, show: state, block: block});
            return false;
        });


	});
		
	</script>

@endsection