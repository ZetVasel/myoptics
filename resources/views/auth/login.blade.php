<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Войти</title>

		@section('styles')
			<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<!-- Optional theme -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
			<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
			<link rel="stylesheet" href="/admin/css/login.css">
			<link rel="stylesheet" href="/admin/css/animate.css">
		@show
	</head>
	<body>
		<div id="bluebox">
			<div class="container animated  flipInY">
	            {!! Form::open(array('class' => 'form-signin')) !!}

	            @if (!$errors->isEmpty())
	            <div class="alert alert-danger">
	                @foreach ($errors->all() as $error)
	                <p>{{ $error }}</p>
	                @endforeach
	            </div>
	            @endif
	        	<div style="text-align: center; margin-bottom: 30px;"><a href="#"><img src="/admin/img/dvacom_white.png" alt="dvacom"> </a></div>

	                {!! Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Email')) !!}
	                {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Пароль')) !!}

	                <button  class="btn btn-lg btn-default pull-right" type="submit"><span class="glyphicon glyphicon-chevron-right"></span> войти</button>
	            {!! Form::close() !!}
	        </div>
		</div>
		@section('scripts')
			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		@show
	<body>
</html>