@extends('admin.layout')

@section('main')

	<h1><a href="/master/products?page={{ $prod_page }}" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1><br>

	@if(Session::has('message'))
		<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
	@endif

	@include('errors.formErrors')

	{!! Form::model( $post, array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true) ) !!}

	<ul class="nav nav-tabs">
	  <li class="active"><a href="#tab_a" data-toggle="tab">Запись</a></li>
	  <li><a href="#tab_b" data-toggle="tab">Параметры страницы</a></li>
	  @if( Request::segment(3) != 'add')
	  	<li><a href="/master/products/image/{{ $post->id }}">Изображения товара</a></li>
	  @endif
	  <li><a href="#tab_c" data-toggle="tab">Сопутствующие товары</a></li>
	</ul>
	<div class="tab-content">

			<div class="tab-pane active" id="tab_a">
				<div class="form-group">&nbsp;</div>

				<div class="tab-content">
					<div class="tab-pane active">
						<!-- <div class="form-group" data-id="{{ $post->id }}">
							<label for="stock" class="col-sm-2 control-label">Наличие товара</label>
							<div class="col-sm-2">
								<a title="Наличие товара" class="in_stock btn @if( $post->in_stock )btn-success visible @else btn-danger @endif">
									<span class="glyphicon @if( $post->in_stock ) glyphicon-ok @else glyphicon-remove @endif"></span>
								</a>
							</div>
						</div> -->
						<div class="form-group text-right padding-15 margin-top-50" style="margin-right:0;" data-id="{{ $post->id }}">
						<a title="Показывать как новинку" class="new btn @if( $post->new )btn-success visible @else btn-default @endif"><span class="glyphicon glyphicon-asterisk"></span></a>
						<a title="Показывать как скидку" class="hit btn @if( $post->discount ) btn-danger visible @else btn-default @endif"><span class="glyphicon glyphicon-star"></span></a>
						 <a title="Показывать как распродажа" class="promo btn @if( $post->sale )btn-danger visible @else btn-default @endif"><span class="glyphicon glyphicon-flag"></span></a>

						</div>

						<div class="form-group">
							{!! Form::label('name', 'Название', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								{!! Form::text('name',  $post->name, array('class'=>'form-control', 'required') ) !!}
							</div>
						</div>

						<div class="form-group">
							<label for="categories" class="col-sm-2 control-label">Категории</label>
							<div class="col-sm-10">
								<select multiple="multiple" id="categories" name="categories[]" class="chosen-select form-control" data-placeholder=" ">
									@foreach( $categories as $cat )
										<option value='{{ $cat->id }}' @if( in_array( $cat->id, $post->categories ) ) selected @endif>{{ $cat->name }}</option>
									@endforeach
								</select>
							</div>
						</div>


						<div class="form-group">
							{!! Form::label('code', 'Артикул', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								{!! Form::text('code',  $post->code, array('class'=>'form-control') ) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('price', 'Цена, ГРН', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								{!! Form::text('price',  $post->price, array('class'=>'form-control number', 'required') ) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('oldPrice', 'Старая цена', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								<input class="form-control number" type="text" maxlength="6" name ="oldPrice" id="oldPrice" value="{{$post->oldPrice}}">
							</div>
						</div>
						<!-- <div class="form-group">
							<label for="new_price" class="col-sm-2 control-label">Новая цена</label>
							<div class="col-sm-10">
								<label id="new_price"></label>
							</div>
						</div> -->

						<div class="form-group">
							{!! Form::label('description', 'Краткое описание', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								{!! Form::text('description',  $post->description, array('class'=>'form-control') ) !!}
							</div>
						</div>

						<div class="form-group">
							{!! Form::label('body', 'Полное описание', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								{!! Form::textarea('body',  $post->body, array('class'=>'form-control editor') ) !!}
							</div>
						</div>
						<div class="row">
							{!! Form::label('specification', 'Характеристики', array('class'=>'col-sm-2 control-label') ) !!}
							<div class="col-sm-10">
								<div class="specification_table_wr">
									<div class="row">
										<div class="col-sm-2">Характеристика</div>
										<div class="col-sm-2">Значение</div>
									</div>
									@if( isset($post->specification) && count( $post->specification) > 0 &&  $post->specification != '' )
										@foreach( $post->specification as $k => $pack )
											<div class="specification_table row">
												<div class="col-sm-2"><input type="text" name="row_table[{{$k}}][]" value="{{$pack['name']}}" class="form-control"></div>
												<div class="col-sm-2"><input type="text" name="row_table[{{$k}}][]" value="{{$pack['value']}}" class="form-control"></div>
											</div>
										@endforeach
									@else
										<div class="specification_table row">
											<div class="col-sm-2"><input type="text" name="row_table[0][]" value=""  class="form-control"></div>
											<div class="col-sm-2"><input type="text" name="row_table[0][]" value=""  class="form-control"></div>
										</div>
									@endif
								</div>
								<div class="col-sm-1">
									<a href="#" id="add_specification" class="glyphicon glyphicon-plus-sign"></a>
								</div>
							</div>	
						</div>						
<!--  -->
						<div id="input_blocks">
							<div class="row names">
								<div class="col-sm-6 col-sm-offset-3">Параметры фильтрации</div>
							</div>

							@foreach( $features as $feat )
							<div class="form-group input_row">
								<label for="id{{ $feat->id }}" class="col-sm-2 control-label">{{ $feat->name }}</label>

								<div class="col-sm-7">
									<select multiple="multiple" name="feature[{{ $feat->id }}][]" id="id{{ $feat->id }}" class="feature chosen-select form-control">
										<option value=""></option>
											@foreach( $feat->variants as $v )
												<option value="{{ $v->name }}" @if( isset($options[$feat->id] ) && in_array($v->name, $options[$feat->id]) ) selected @endif>{{ $v->name }}</option>
											@endforeach
									</select>
								</div>
							</div>
							@endforeach
						</div>

<!--  -->


						<!--  -->
						<div id="input_blocks">
							<div class="row names">
								<div class="col-sm-6 col-sm-offset-3">Характеристики заказа</div>
							</div>

							@foreach( $charlist as $char )
							<div class="form-group input_row">
								<label for="id{{ $char->id }}" class="col-sm-2 control-label">{{ $char->name }}</label>

								<div class="col-sm-7">
									<select multiple="multiple" name="charlist[{{ $char->id }}][]" id="id{{ $char->id }}" class="feature chosen-select form-control">
										<option value=""></option>
											@foreach( $char->variants as $v )
												<option value="{{ $v->name }}" @if( isset($charlist_options[$char->id] ) && in_array($v->name, $charlist_options[$char->id]) ) selected @endif>{{ $v->name }}</option>
											@endforeach
									</select>
								</div>
							</div>
							@endforeach
						</div>

<!--  -->



						
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
					  <input type="text" class="form-control" id="inputSlug" name="slug" value="{{ $post->slug }}" required>
					 </div>
					</div>
				  </div>


					<div class="tab-content">
						<div class="tab-pane active" id="stab_ua">
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
				  	</div>
			</div>
			<div class="tab-pane" id="tab_c">
				<div class="row">
					<div class="col-sm-4 text-center">Все товары</div>
					<div class="col-sm-4 text-center">Похожие товары</div>
				</div>
				<select multiple="multiple" id="my-select" name="alike[]">
					@foreach( $alike_prod as $ap )
						<option value='{{ $ap->id }}' @if( in_array( $ap->id, $post->alike ) ) selected @endif>{{ $ap->name }}</option>
					@endforeach
				</select>
			</div>
	</div><!-- tab content -->

	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-success">Сохранить</button>
		</div>
	  </div>


	{!! Form::close() !!}
@endsection

@section('styles')
	<link rel="stylesheet" href="/dashboard/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="/dashboard/js/chosen/chosen.min.css">
@endsection

@section('scripts')

	<script src="/admin/js/jquery.multi-select.js"></script>
	<!--<script src="/dashboard/js/bootstrap-datetimepicker.min.js"></script>-->
	<script src="/dashboard/js/chosen/chosen.jquery.min.js"></script>
	<script src="/admin/js/jquery.quick-search.js"></script>

	<script>

		$(document).ready(function(){
		$('#my-select').multiSelect({
			selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Имя товара'>",
			selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Имя товара'>",
			afterInit: function(ms){
			var that = this,
			    $selectableSearch = that.$selectableUl.prev(),
			    $selectionSearch = that.$selectionUl.prev(),
			    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
			    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

			that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
			.on('keydown', function(e){
			  if (e.which === 40){
			    that.$selectableUl.focus();
			    return false;
			  }
			});

			that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
			.on('keydown', function(e){
			  if (e.which == 40){
			    that.$selectionUl.focus();
			    return false;
			  }
			});
			},
			afterSelect: function(){
			this.qs1.cache();
			this.qs2.cache();
			},
			afterDeselect: function(){
			this.qs1.cache();
			this.qs2.cache();
			}
		});
		$('#categories').chosen();
		$('.feature').chosen();

		calc_newprice();

		$('#p_discount').change(function(){
			calc_newprice();
		});
		$(".number").keypress(function (e){
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && (e.which != 46) ) {
				return false;
			}
		});

		$('#p_discount').keyup(function(){
			calc_newprice();
		});

		$('#add_specification').click(function(){

			$('.specification_table_wr').append( $('.specification_table').first().clone() );
			$('.specification_table').last().find('input').val('');
			$('.specification_table').last().find('input').attr('name', 'row_table['+ ($('.specification_table').length - 1) +'][]');
			return false;
		});

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

		function calc_newprice(){
			var old_price = parseFloat($('#price').val());
			var discount = parseFloat($('#p_discount').val());
			var new_price = (old_price * ((100 - discount)/100));
			// new_price = Math.round(new_price);
			$('#new_price').text(new_price.toFixed(2));
		}

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


		// хит товар
		$('.hit').click( function() {

			var icon = $(this);
			var state = icon.hasClass('visible') ? 0 : 1;
			if(state) {
				icon.removeClass('btn-default');
				icon.addClass('btn-danger visible');
			} else {
				icon.removeClass('visible btn-danger');
				icon.addClass('btn-default');
			}

            $('.new').removeClass('visible btn-success');
            $('.new').addClass('btn-default');


            $('.promo').removeClass('visible btn-danger');
            $('.promo').addClass('btn-default');


			$.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), discount: state });
			return false;
		});


		//НОВЫЙ
		$('.new').click( function() {

			var icon = $(this);
			var state = icon.hasClass('visible') ? 0 : 1;
			if(state) {
				icon.removeClass('btn-default');
				icon.addClass('btn-success visible');
			} else {
				icon.removeClass('visible btn-success');
				icon.addClass('btn-default');
			}

            $('.hit').removeClass('visible btn-danger');
            $('.hit').addClass('btn-default');


            $('.promo').removeClass('visible btn-danger');
            $('.promo').addClass('btn-default');



			$.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), new: state });
			return false;
		});

		//TOP
		$('.promo').click( function() {
			var icon = $(this);
			var state = icon.hasClass('visible') ? 0 : 1;
			if(state) {
				icon.removeClass('btn-default');
				icon.addClass('btn-danger visible');
			} else {
				icon.removeClass('visible btn-danger');
				icon.addClass('btn-default');
			}

            $('.hit').removeClass('visible btn-danger');
            $('.hit').addClass('btn-default');


            $('.new').removeClass('visible btn-success');
            $('.new').addClass('btn-default');

			$.post( '/master/products', { _token: '{{ Session::token() }}', id: $(this).parent().data('id'), sale: state });
			return false;
		});


	});


	</script>

	@include('admin.tinymce_init')
@endsection