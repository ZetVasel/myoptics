@extends('admin.layout')

@section('main')

	<h1><a href="/master/feedback_management" class="glyphicon glyphicon-circle-arrow-left"></a>
		{{ $title }}
		<span class="label label-default">
			{{ $subscribers_count }}
			{{ Lang::choice('подписчик|подписчика|подписчиков', $subscribers_count, array(), 'ru') }}
		</span>
	</h1>

	<ul class="nav nav-tabs">
	  <li class="active"><a href="#tab_a" data-toggle="tab">Подписчики</a></li>
	  <li><a href="#tab_b" data-toggle="tab">Отправить сообщение</a></li>
	</ul>

	<div class="tab-content">


		@if(Session::has('message'))
			<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
		@endif

		<div class="tab-pane active" id="tab_a">

			@if( count($subscribers)>0 )
				{!! Form::open(['class' => 'list']) !!}
					<table class="table">
						<tr>
							<th style="width: 30%;">Имя</th>
							<th style="width: 30%;">Email</th>
							<th style="text-align: right;">Удаление</th>
						</tr>

						@foreach ($subscribers as $subscriber)
						<tr>
							<td>
								<input name="check[]" value="{{ $subscriber->id }}" type="checkbox">{{{ $subscriber->id }}}
							</td>
							<td>{{{ $subscriber->mail }}}</td>
							<td style="text-align: right;">
								<button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $subscriber->id }}"><span class="glyphicon glyphicon-remove"></span></button>
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
				{!! $subscribers->render() !!}
			
			@else
				<div class="alert alert-warning" role="alert">
				 Нет подписчиков
				</div>
			@endif

		</div>
		<!-- tab a end  -->

		<div class="tab-pane" id="tab_b">

			<div class="form-group">&nbsp;</div>

			{!! Form::open(array( 'class' => 'form-horizontal', 'role' => 'form', 'action' => 'Admin\AdminSubscribeController@postSendEmail') ) !!}


				
				<div class="form-group">
					{!! Form::label('theme', 'Тема сообщения', array('class'=>'col-sm-2 control-label') ) !!}
					<div class="col-sm-10">
						{!! Form::text('theme', null, array( 'class'=>'form-control')) !!}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('text', 'Текст сообщения', array('class'=>'col-sm-2 control-label') ) !!}
					<div class="col-sm-10">
						{!! Form::textarea('text',  null, array('class'=>'form-control editor') ) !!}
					</div>
				</div>
				<div class="col-sm-2"></div>
				<div class="form-group col-sm-10">
				 
					<div class="col-sm-4 text-center">Все подпищики</div>
					<div class="col-sm-4 text-center">Отправить нескольким</div>
					<select multiple="multiple" id="my-select" name="subscriber[]">
						@foreach ($subscribers as $subscriber)
							<option value='{{ $subscriber->id }}'>{{ $subscriber->mail }}</option>
						@endforeach
					</select> 
				</div>

				<!-- <div class="form-group">
					<label for="cat_id" class="col-sm-2 control-label">Категория товара:</label>
					<div class="col-sm-10">
						<select name="cat_id" id="cat_id" class="form-control">
							@foreach($subscribers as $cat)
									<option value="{{ $cat->id }}">{{ $cat->email }}</option>
							@endforeach
						</select>
					</div>
				</div> -->



				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success">Отправить</button>
					</div>
				</div>

			{!! Form::close() !!}

		</div>
		<!-- tab b end -->

	</div>
	<!--tab content  -->

	<script src="/admin/js/jquery.multi-select.js"></script>
	<script src="/admin/js/jquery.quick-search.js"></script>
	<script>

	// multiselect
		$('#my-select').multiSelect({
			selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Е-мейл'>",
			selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Е-мейл'>",
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

		meta_title_touched = false;
		url_touched = false;

		$('input[name="slug"]').change(function() { url_touched = true; });
		$('input[name="name"]').keyup(function() { 
			if(!url_touched)
				$('input[name="slug"]').val(generate_url()); 
				
			if(!meta_title_touched)
				$('input[name="meta_title"]').val( $('input[name="name"]').val() );
			
		});


	 $(function() {



		// удаление записи
		$('.delete').click( function() {
			$('input[type="checkbox"][name*="check"]').attr('checked', false);
			$(this).closest("tr").find('input[type="checkbox"][name*="check"]').attr('checked', true);
			$(this).closest("form").find('select[name="action"] option[value=delete]').attr('selected', true);
			$(this).closest("form").submit();
		});

		// удаление записей
		$("form.list").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;
		});

		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});
	})
	</script>

@include('admin.tinymce_init')
@stop