@extends('admin.layout')

@section('main')

	<h1><a href="/master/offers" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1><br>

	@if(Session::has('message'))
		<div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
	@endif

	@include('errors.formErrors')

	{!! Form::model( $post, array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true) ) !!}
	<div class="row">
		<div class="col-sm-4 text-center">Все товары</div>
		<div class="col-sm-4 text-center">Товары для акции</div>
	</div>
	<select multiple="multiple" id="my-select" name="related[]">
		@foreach( $products as $ap )
			<option value='{{ $ap->id }}' @if( in_array( $ap->id, $post->related ) ) selected @endif>{{ $ap->name }}</option>
		@endforeach
	</select>
<!-- 	<select multiple="multiple" id="my-select" name="related[]">
		@foreach( $products as $ap )
			<option value='{{ $ap->id }}' @if( in_array( $ap->id, $post->related ) ) selected @endif>{{ $ap->name }}</option>
		@endforeach
	</select>
 -->
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-success">Сохранить</button>
		</div>
	  </div>

	{!! Form::close() !!}
@endsection

@section('styles')
	<link rel="stylesheet" href="/admin/css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="/dashboard/js/chosen/chosen.min.css">
@endsection

@section('scripts')
<!--         "jquery.multi-select.js",
        "jquery.quick-search.js", -->
	<script src="/admin/js/jquery.multi-select.js"></script>
	<script src="/admin/js/jquery.quick-search.js"></script>
	<script src="/dashboard/js/chosen/chosen.jquery.min.js"></script>
	<script>
		// multiselect
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
	});


	</script>

	@include('admin.tinymce_init')
@endsection