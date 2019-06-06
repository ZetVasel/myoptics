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

			<form method="POST" action="/edit-info">
				{!! csrf_field() !!}

				<div class="form_group">
					<label for="firstName"></label>
					<input type="text" value="{{ $user->firstName }}" name="firstName" id="firstName" placeholder="Введите Ваше Имя">
				</div>


				<div class="form_group">
					<label for="lastName"></label>
					<input type="text" value="{{ $user->lastName }}" name="lastName" id="lastName" placeholder="Введите Вашу Фамилию">
				</div>


				<div class="form_group">
					<label for="middleName"></label>
					<input type="text" value="{{ $user->middleName }}" name="middleName" id="middleName" placeholder="Введите Ваше Отчество">
				</div>

				<div class="form_group">
					<label for="city"></label>
					<input type="text" value="{{ $user->city }}" name="city" id="city" placeholder="Введите Ваше Город">
				</div>

				<div class="form_group">
					<label for="address"></label>
					<input type="text" value="{{ $user->address }}" name="address" id="address" placeholder="Введите Ваш Адрес">
				</div>





				<div class="form_group">
					<label for="email" class="subscr_email"></label>
					<input type="text" value="{{ $user->email }}" name="email" id="email" placeholder="Введите Ваш email">
				</div>

				<div class="form_group">
					<label for="phone" class="subscr_phone"></label>
					<input type="text" value="{{ $user->phone }}" name="phone" id="phone" placeholder="Введите Ваш телефон">
				</div>

				<div class="form_group">
					<label for="password" class="password"></label>
					<input type="text" name="password" id="password" placeholder="Новый пароль">
				</div>
				<input type="submit" value="Изменить">
			</form>
		</div>
	</div>
</div>
	<!-- wrapper end -->

@endsection

@section('styles')
	<link rel="stylesheet" href="/frontend/css/contacts.css">
	<link rel="stylesheet" href="/frontend/css/user.css">
@endsection