@extends('frontend.layout')

@section('main')

	<div class="breadCrumbBg contacts">
		<div class="container">
			<div class="row">
				<ul class="breadCrumbs col-xs-12">
					<li><a href="/">Главная</a></li>
					<li><a href="/{{ $page->slug }}">{{ $page->name }}</a></li>
				</ul>
			</div>
		</div>
	</div>


	<div class="container">
		<div class="row">
		<div class="pers_area col-xs-12">

			@if (Auth::check())
					<div class="user_info fleft">
						<table>

							@if( $user->firstName )
								<tr>
									<td>Имя</td>
									<td>{{ $user->firstName }}</td>
								</tr>
							@endif

							@if( $user->lastName )
								<tr>
									<td>Фамилия</td>
									<td>{{ $user->lastName }}</td>
								</tr>
							@endif

							@if( $user->middleName )
								<tr>
									<td>Отчество</td>
									<td>{{ $user->middleName }}</td>
								</tr>
							@endif




							<tr>
								<td>Электронная почта</td>
								<td>{{ $user->email }}</td>
							</tr>
							@if( $user->phone )
								<tr>
									<td>Телефон</td>
									<td>{{ $user->phone }}</td>
								</tr>
							@endif
							@if( count( $orders ) )
								<tr>
									<td>Накопленная сумма</td>
									<td>{{ $total_ordered }} грн @if($total_ordered < $settings->sum) <i style="font-size: 13px;">(нужно еще купить товаров на <b style="font-weight: bold">{{ $settings->sum - $total_ordered }}</b> грн для скидки в <b style="font-weight: bold">{{ $settings->discount }}</b> %) </i> @endif </td>
								</tr>
							@endif
							@if( $total_ordered >= $settings->sum )
								<tr>
									<td>Ваша скидка</td>
									<td>{{ $settings->discount }} %</td>
								</tr>
							@endif
						</table>
					</div>
					<div class="relativeBlock">
							<!-- user_info end -->
							<div class="right_section fright">
								<a href="/edit-info">Редактировать личные данные</a>
								<a href="/logout">Выйти</a>
							</div>
							<div class="notification">
								<a href="/notifications">Напоминание о замене линз</a>
								<a href="/service">Сервисная заявка</a>
							</div>
					</div>
				<!-- fright end -->
				</div>
				<!-- clearfix end -->
				@if( count( $orders ) )
				<table class="ordered_pr">
					<tr>
						<td class="hidden-xs">Номер заказа</td>
						<td>Дата заказа</td>
						<td>Заказанные товары</td>
						<td>Информация о заказе</td>
						<td class="hidden-xs">Статус заказа</td>
					</tr>
					@foreach( $orders as $order )
						<tr>
							<td class="hidden-xs">№ {{ $order->id }}</td>
							<td>{{ $order->created_at->format('d.m.Y h:i') }}</td>
							<td>
								@foreach( $order->orderedproducts as $op )


									@if( $op->product_type != 5 )
										<a href="/product/{{ $op->slug }}" target="_blank">
											<img src="@if( $img = unserialize($op->imgs)[$op->main_img] ) /uploads/products/sm/{{ $img }} @else /uploads/products/sm/no_image.png @endif" alt="{{ $op->product_name}}">
										</a>
									@endif
								@endforeach
								<!--  -->
							</td>
							<td>{{ count($order->orderedproducts) }} {{ Lang::choice('товар|товара|товаров', count($order->orderedproducts), array(), 'ru') }} на {{ $order->total_cost }} грн</td>
							<td class="hidden-xs">
								@if( $order->status == 0 )
									<span class="red">Новый</span>
								@elseif( $order->status == 1 )
									<span class="brown">В обработке</span>
								@else
									<span class="green">Выполнен</span>
								@endif
							</td>
						</tr>
					@endforeach
				</table>
				@endif
			@else
				@if( Session::has('error') || $errors->any() )
					@foreach( $errors->all() as $error )
						<li>{{ $error }}
					@endforeach
				@endif

			@endif
	</div>
	</div>
	<!-- wrapper end -->

@endsection

@section('scripts')
<script>

	var status = 1;
	$('.toggle').click(function(){
		if( status == 1 ){
			$(this).text('Войти');
			$('#login_form').hide(300);
			$('#reg_form').show(300);
			status = 0;
		}
		else{
			$(this).text('Зарегистрироваться');
			$('#reg_form').hide(300);
			$('#login_form').show(300);
			status = 1;
		}
		return false;
	});


</script>
@endsection

@section('styles')
	<link rel="stylesheet" href="/frontend/css/contacts.css">
	<link rel="stylesheet" href="/frontend/css/user.css">
@endsection
