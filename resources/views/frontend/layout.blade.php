<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    @if (  isset( $page->meta_title ) )<title>{{{ $page->meta_title }}}</title>@endif
    @if (  isset( $page->meta_keywords ) )<meta name="keywords" content="{{{ $page->meta_keywords }}}">@endif
    @if (  isset( $page->meta_description )  )<meta name="description" content="{{{ $page->meta_description }}}">@endif
	@if (Input::has('page'))  <link rel="canonical" href="http://myoptics.com.ua/news" /> @endif  
     <!--css-->
    <link rel="stylesheet" href="/frontend/css/layout.css">
    @section('styles')
    @show
    <link rel="stylesheet" href="/frontend/css/media.css">
	<style type="text/css">
    .overlayCart {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 500ms;
        visibility: hidden;
        opacity: 0;
        z-index: 999;
    }
    .overlayCart:target {
        visibility: visible;
        opacity: 1;
    }

    .cartSuccess {
        margin: 170px auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        width: 30%;
        position: relative;
        transition: all 5s ease-in-out;
    }

    .cartSuccess h2 {
        margin-top: 0;
        color: #333;
        font-family: Tahoma, Arial, sans-serif;
    }
    .cartSuccess .close {
        position: absolute;
        top: 20px;
        right: 30px;
        transition: all 200ms;
        font-size: 30px;
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }
    .cartSuccess .close:hover {
        color: #06D85F;
    }
    .cartSuccess .content {
        max-height: 30%;
        overflow: auto;
    }	

    @media screen and (max-width: 700px){
        .box{
            width: 70%;
        }
        .cartSuccess{
            width: 70%;
        }
    }
</style>
@include('frontend.analyticstracking')      
</head>
<body>

<header>
    <div class="container">
        <div class="row">
            <div class="top-content col-xs-12">
                <div class="row">
                    <div class="logo col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <a href="/">
                            <img src="/uploads/logo/{{ $settings->logo }}" alt="#">
                        </a>
                    </div>

                    <div class="right-top-content reset-padding col-lg-4 col-lg-offset-4 col-md-offset-2 col-md-6 col-sm-offset-2 col-sm-6 col-xs-6">
                        <div class="phones hidden-xs">
                            <span class="item">{{ $settings->phone1 }}</span>
                            <span class="item">{{ $settings->phone2 }}</span>
                        </div>
                        <div class="icons">
                            <div class="search"></div>
                            @if(Auth::check())
                                <a href="/personal-area">
                                    <div class="profile"></div>
                                </a>
                            @else
                                <div class="profile"></div>
                            @endif
                            <a href="/cart">
                                <div class="busket">
                                    @if( $cart_info['quantity'] > 0 )
                                        <span class="count">{{ $cart_info['quantity'] }}</span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <form id="searchForm" action="/search" method="post">
                    {!! csrf_field() !!}
                    <input type="text" name="search" placeholder="Введите слово для поиска" required>
                    <input type="submit" value="Поиск">
                    <div title="Закрыть" id="closeSearch"></div>
                </form>

            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <div class="burger col-md-6 col-sm-6 col-sm-offset-3 col-md-offset-3 hidden-lg" id="burger"><span>Меню</span></div>

            <ul class="menu col-lg-12 col-lg-offset-0 col-md-6 col-sm-6 col-sm-offset-3 col-md-offset-3 col-xs-12">
                <li><a @if( Request::segment(1) == '' ) class="active" @endif href="/">Главная</a></li>


                @foreach($categories as $cat)
                    <li><a @if( Request::segment(2) == $cat->slug ) class="active" @endif href="/category/{{ $cat->slug }}">{{$cat->name}}</a>
                        @if(count( $cat->children ) )
                            <ul class="child">
                                @foreach(  $cat->children as $subcat )
                                    <li>
                                        <a href="/category/{{ $subcat->slug }}">{{$subcat->name}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach


                @foreach( $pages as $pg )
                    @if( $pg->type == 'main' && $pg->slug != '/' && !in_array($pg->id, [41]))
                        <li><a @if( Request::segment(1) == $pg->slug ) class="active" @endif href="/{{ $pg->slug }}">{{ $pg->name }}</a></li>
                    @endif
                @endforeach

                {{--<li>--}}
                    {{--<a href="#">Контактные линзы</a>--}}
                    {{--<ul class="child">--}}
                        {{--<li><a href="#">Bausch&Lomb</a></li>--}}
                        {{--<li><a href="#">Maxima Optics</a></li>--}}
                        {{--<li><a href="#">Henson</a></li>--}}
                        {{--<li><a href="#">Alcon</a></li>--}}
                        {{--<li><a href="#">Avizor</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li><a href="#">Растворы и капли</a></li>--}}
                {{--<li><a href="#">Аксессуары для линз</a></li>--}}
                {{--<li><a href="#">Оплата и доставка</a></li>--}}
                {{--<li><a href="#">Статьи</a></li>--}}
                {{--<li><a href="#">О нас</a></li>--}}
                {{--<li><a href="#">Контакты</a></li>--}}
            </ul>
        </div>
    </div>
</header>

        @yield('main')

<footer>
    <div class="border-container">
        <div class="container">
            <div class="row">
                <div class="footer-nav col-xs-12">
                    <div class="row">
                        <div class="footLogo col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <img src="/uploads/logo/{{ $settings->logo }}" alt="#">
                        </div>
                        <ul class="footMenu col-lg-9 col-md-8 col-sm-8 hidden-xs">
                            <li><a href="/">Главная</a></li>

                            {{ $i = 0 }}
                            @foreach($footCatArray as $item)
                                @if($i++ < 3)
                                <li>
                                    <a href="@if(isset($item['show_block_1']))/category/@endif{{ $item['slug'] }}">{{ $item['name'] }}</a>
                                </li>
                                @else
                                <li>
                                    <a href="/{{ $item['slug'] }}">{{ $item['name'] }}</a>
                                </li>
                                @endif
                            @endforeach
                            {{--<li><a href="#">Растворы и капли</a></li>--}}
                            {{--<li><a href="#">Аксессуары для линз</a></li>--}}
                            {{--<li><a href="#">Оплата и доставка</a></li>--}}
                            {{--<li><a href="#">Статьи</a></li>--}}
                        </ul>
                    </div>
                </div>


                <div class="footerInfo col-xs-12">
                    <div class="row">
                        <div class="contactsInfo col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <div class="phones">
                                @if(!empty($settings->phone1))
                                    <div class="item">{{ $settings->phone1 }}</div>
                                @endif
                                @if(!empty($settings->phone2))
                                    <div class="item">{{ $settings->phone2 }}</div>
                                @endif
                            </div>
                            @if(!empty($settings->email))
                                <div class="mail">{{ $settings->email }}</div>
                            @endif
                            @if(!empty($settings->address))
                                <div class="address">{{ $settings->address }}</div>
                            @endif
                        </div>
                        <div class="footText col-lg-9 col-md-8 col-sm-8 hidden-xs">
                            {!! $settings->footText !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="copir col-xs-12">
                <span class="pull-left">{{ $settings->copir }}</span><span class="pull-right dva_com">Раскрутка сайта – <a href="https://dvacom.net" rel="nofollow" >https://dvacom.net</a></span>
            </div>
        </div>
    </div>


</footer>


<div class="overlay"></div>
<div class="loginForm" id="loginForm">
    <div id="closeLoginForm"></div>
    <div class="title">
        Вход в личный кабинет
    </div>

    <form action="/login" method="post">
        {!! csrf_field() !!}
        <label for="mail">Эл. почта</label>
        <input type="text" id="mail" name="email" placeholder="konstantin.konstantionovsky@gmail.com" required>

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>

        <div class="formBottom">
            <input type="checkbox" name="rememberMe" class="rememberMe" id="rememberMe">
            <label class="rememberLabel" for="rememberMe">Запомнить меня</label>
            <a href="/password/email">Забыли пароль?</a>
            <a class="reg" href="/reg">Регистрация</a>
        </div>

        <div class="loginWithFB">
            <a href="{{ $facebookAuthLink }}" rel="nofollow" >Войти c помощью facebook</a>
        </div>
        <div class="loginWithGoogle">
            <a href="{{ $googleAuthLink }}" rel="nofollow">Войти  c помощью google</a>
        </div>


        <input type="submit" value="Войти">


    </form>

</div>



@if(Session::has('message'))
    <div id="popup1" class="overlayCart">
        <div class="cartSuccess">
            <div class="content">
                {{ Session::get('message') }}
            </div>
        </div>
    </div>
@endif


       

        <!--js-->
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <!--пользоватские скрипты-->
        <script type="text/javascript" src="/frontend/js/layout.js"></script>
<script type="text/javascript">
    @if(!Auth::check())
        //профиль
        $('.profile').click(function () {
            $(this).toggleClass('active');
            $('#loginForm').fadeToggle(200);
            $('.overlay').fadeToggle('fast');
        });
    @endif


    @if(Session::has('message'))

		$(document).ready(function () {
        $('.overlayCart').css({ "opacity" : "1", "visibility" : "visible"});
        setTimeout(function () {
            $('#popup1').fadeOut(800);
        }, 1500);
    });
    @endif

</script>
        @section('scripts')
        @show
<!-- QnkgTmlrb2xheSBUc3Vya2Fu -->
    </body>
</html>