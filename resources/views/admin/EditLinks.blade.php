@extends('admin.layout')

@section('main')

	@if(Session::has('success'))
		<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
	@elseif(Session::has('error'))
		<div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
	@endif

	 <h1><a href="/master/links" class="glyphicon glyphicon-circle-arrow-left"></a>Линки</h1>
		<!--  -->
		{!! Form::model($remote,array( 'id' => 'upload_form', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ) ) !!}
			
			<!-- <div class="form-group">
				{!! Form::label('type', 'Тип', array('class'=>'col-sm-2 control-label') ) !!}
				<div class="col-sm-10">
					{!! Form::select('type', array('Социальные сети' => 'Социальные сети'), $type,  array( 'class'=>'form-control')) !!}
				</div>
			</div> -->
			<div class="form-group">
				{!! Form::label('name', 'Название', array('class'=>'col-sm-2 control-label') ) !!}
				<div class="col-sm-10">
					{!! Form::text('name', $remote->name, array('class'=>'form-control') ) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('link', 'Ссылка', array('class'=>'col-sm-2 control-label') ) !!}
				<div class="col-sm-10">
					{!! Form::text('link',  $remote->link, array('class'=>'form-control') ) !!}
				</div>
			</div>
			
			<div class="form-group" >
				<div class="col-sm-offset-2 col-sm-10">
			  		<button type="submit" class="btn btn-success">Сохранить</button>
				</div>
			</div>
		{!! Form::close() !!}	

		<script>
	// meta_title_touched = false;
	// url_touched = false;

	// $('input[name="slug"]').change(function() { url_touched = true; });



	// $('input[name="name"]').keyup(function() { 
	// 	if(!url_touched)
	// 		$('input[name="slug"]').val(generate_url()); 
			
	// 	if(!meta_title_touched)
	// 		$('input[name="meta_title"]').val( $('input[name="name"]').val() );
		
	// });
		


	// $('input[name="meta_title"]').change(function() { meta_title_touched = true; });

	// function generate_url()
	// {
	// 	url = $('input[name="name"]').val();
	// 	url = url.replace(/[\s]+/gi, '-');
	// 	url = translit(url);
	// 	url = url.replace(/[^0-9a-z_\-]+/gi, '').toLowerCase();	
	// 	return url;
	// }

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
</script>


@include('admin.tinymce_init')
@endsection