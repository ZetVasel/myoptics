<!DOCTYPE html>
<html lang="en">
<head> 
	<meta charset="utf-8">
	<title>{{ $title }} @if( isset( $site_offline ) && $site_offline == 1 )OFFLINE @endif</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link href="/dashboard/css/bootstrap-switch.min.css" rel="stylesheet">
	<link href="/dashboard/css/magnific.css" rel="stylesheet">
	<link href="/admin/css/style.css" rel="stylesheet">
	<link href="/dashboard/css/animate.css" rel="stylesheet">
	<link href="/dashboard/bower_components/angular/angular-ui-tree.min.css" rel="stylesheet">
	<script src="/dashboard/js/jquery.js"></script>
	@section('styles')
	@show

	<script src="/dashboard/js/magnific.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="/dashboard/js/bootstrap.filestyle.js"></script>
	<script src="/dashboard/js/bootstrap-switch.min.js"></script>
	<script src="/dashboard/js/bootstrap-growl.min.js"></script>
	@section('scripts')
	@show
	<!--[if lt IE 9]>
		<script src="/frontend/js/html5shiv.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li class="icon">
					<a class="navbar-brand" href="/master/settings">
						<i class="fa fa-cog fa-2x"></i>
					</a>
				</li>
				<li @if( in_array( Request::segment(2), ['','pages', 'articles', 'news', 'slider'] ) ) class="active"@endif>
					<a href="/master">Управление контентом<span class="sr-only">(current)</span></a>
				</li>
				<li @if( in_array( Request::segment(2), ['products_management','categories', 'brands', 'products'] ) ) class="active"@endif>
					<a href="/master/products_management">Управление товаром</a>
				</li>
				<li @if( Request::segment(2) == 'orders' ) class="active"@endif>
					<a href="/master/orders">Заказы <span>{{ $new_orders }}</span></a>
				</li>
				<li @if( in_array( Request::segment(2), ['feedback_management','reviews', 'feedback', 'subscrib', 'histories', 'subscribers', 'product_reviews', 'accessory_reviews'] ) ) class="active"@endif>
					<a href="/master/feedback_management">Обратная связь <span> {{$new_feedbacks}} </span> </a>
				</li>
				{{--<li @if( in_array( Request::segment(2), ['links'] ) ) class="active" @endif>--}}
					{{--<a href="/master/links">Соцсети<span class="sr-only">(current)</span></a>--}}
				{{--</li>--}}
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="icon gotosite"><a target="_blank" href="/"><div class="goto">Перейти на сайт</div><i class="fa fa-arrow-circle-o-right fa-3x"></i></a></li>
				<li><a href="/auth/logout" class="logout"><i class="fa fa-sign-in"></i> Выход</a></li>
				<li class="icon">
					<a class="navbar-brand" href="/master/users">
						<i class="fa fa-user fa-2x"></i>
					</a>
				</li>
			</ul>
		</div>
		<!-- /.container-fluid -->
	</nav>

	<div class="container-fluid">
		<div id="show_page">
			@yield('main')
		</div>
	</div>

</body>
</html>