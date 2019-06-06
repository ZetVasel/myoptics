@extends('frontend.layout')


@section('main')

    <div class="breadCrumbBg contacts">
        <div class="container">
            <div class="row">
                <ul class="breadCrumbs col-xs-12">
                    <li><a href="/">Главная</a></li>
                    <li><a href="/reg">Регистрация</a></li>
                </ul>

            </div>
        </div>
    </div>

<div class="cartBg cartTwo">
    <div class="container">
        <div class="row">
            <div class="tabs">
                <div class="tabsContent col-xs-12">
                    <div class="item row">
                        <form action="/register" method="post" class="login col-xs-12 register">
                            {!! csrf_field() !!}
                            <div class="email row">
                                <label class="col-lg-2 col-sm-3 col-md-3 col-xs-12" for="emailText">Эл. почта</label>
                                <input class="col-lg-3 col-sm-6 col-md-6 col-xs-12" type="text" name="email" id="emailText" placeholder="konstantin.konstantionovsky@gmail.com" required>
                            </div>

                            <div class="password row">
                                <label class="col-lg-2 col-sm-3 col-md-3 col-xs-12" for="passwordText">Пароль</label>
                                <input class="col-lg-3 col-sm-6 col-md-6 col-xs-12" type="password" name="password" id="passwordText" placeholder="Пароль" required>
                            </div>

                            <div class="password row">
                                <label class="col-lg-2 col-sm-3 col-md-3 col-xs-12" for="firstName">Имя</label>
                                <input class="col-lg-3 col-sm-6 col-md-6 col-xs-12" type="text" name="firstName" id="firstName" placeholder="Имя" required>
                            </div>

                            <div class="password row">
                                <label class="col-lg-2 col-sm-3 col-md-3 col-xs-12" for="phone">Телефон</label>
                                <input class="col-lg-3 col-sm-6 col-md-6 col-xs-12" type="text" name="phone" id="phone" placeholder="Телефон" required>
                            </div>

                            <div class="row">
                                <div class="loginSubmit col-lg-5 col-sm-9 col-md-9 col-xs-12">
                                    <input type="submit" value="Зарегистрироваться">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
    <link rel="stylesheet" href="/frontend/css/cart.css">
    <link rel="stylesheet" href="/frontend/css/contacts.css">
@endsection
