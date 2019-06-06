@extends('frontend.layout')

@section('main')

    @include('frontend._breadcrumbs')

    <div class="lensesContentBg">
        <div class="container">
            <div class="row">

                <div class="rightBar allseen col-lg-12">

                    <div class="row">

                        <!--Список-->
                        <div id="productList" @if(Session::get('show') == 1) style="display: block;" @else style="display: none" @endif class="listProducts col-xs-12">

                            @if(count($products) > 0)

                                @foreach( $products as $prod)
                                    <div class="product col-xs-12">
                                        <div class="item @if($prod->discount == 1) discount @endif @if($prod->new == 1) new @endif @if($prod->sale == 1) sale @endif">
                                            <div class="row">
                                                <div class="image col-lg-4 col-md-5 col-sm-5">
                                                    @if( $prod->image !='')
                                                        <img src="/uploads/products/md/{{ $prod->image }}" alt="{{ $prod->name }}">
                                                    @else
                                                        <img src="/uploads/products/md/no_image.png" alt="#">
                                                    @endif
                                                </div>
                                                <div class="information col-lg-8 col-md-7 col-sm-7">
                                                    <div class="title">{{ $prod->name }}</div>
                                                    <div class="desc">
                                                        {{ strip_tags($prod->description) }}
                                                    </div>
                                                    <div class="charList">
                                                        <? $count = 0; ?>

                                                        @foreach(unserialize($prod->specification) as $dtbl)
                                                            @if($count++ > 2) <? break; ?>  @endif
                                                            <div class="item">
                                                                <div class="name">{{ $dtbl['name'] }}</div>
                                                                <div class="value">{{ $dtbl['value'] }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="infBottom">
                                                        <div class="rating">
                                                            @for($i = 0; $i < 5 ;$i++)
                                                                <div class="item @if($prod->votes !=0) @if($i < (int)($prod->rating_sum / $prod->votes) ) active @endif @endif "></div>
                                                            @endfor
                                                        </div>


                                                        <div class="price">
                                                            @if($prod->oldPrice > 0)
                                                                <div class="old">{{ number_format($prod->oldPrice, 2, ',', '') }}</div>
                                                            @endif
                                                            <div class="now"><span>{{ number_format($prod->price - ($prod->price * $userDiscount), 2, ',', '') }}</span> Грн</div>
                                                        </div>

                                                        <a href="/product/{{ $prod->slug }}" class="button">
                                                            Подробнее
                                                        </a>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            @else
                                <div class="col-xs-12">
                                    <div class="prodEmpty">
                                        Товаров не найдено
                                    </div>
                                </div>
                            @endif

                        </div>




                        <!--сетка-->
                        <div id="productGrid" @if(Session::get('show') == 0) style="display: block" @else style="display: none" @endif class="products col-xs-12">
                            <!--<div class="products">-->
                            @if(count($products) > 0)
                                @foreach( $products as $prod)

                                    <div class="product col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="item @if($prod->discount == 1) discount @endif @if($prod->new == 1) new @endif @if($prod->sale == 1) sale @endif">
                                            <div class="image">
                                                @if( $prod->image !='')
                                                    <img src="/uploads/products/md/{{ $prod->image }}" alt="{{ $prod->name }}">
                                                @else
                                                    <img src="/uploads/products/md/no_image.png" alt="#">
                                                @endif
                                            </div>
                                            <div class="information">
                                                <div class="title">{{ $prod->name }}</div>
                                                <div class="text">
                                                    {{ strip_tags($prod->description) }}
                                                </div>
                                                <div class="prices">
                                                    @if($prod->oldPrice > 0)
                                                        <div class="old">{{ number_format($prod->oldPrice, 2, ',', '') }}</div>
                                                    @endif
                                                    <div class="now"><span>{{ number_format($prod->price - ($prod->price * $userDiscount), 2, ',', '') }}</span> Грн</div>
                                                </div>
                                                <a href="/product/{{ $prod->slug }}" class="button hidden-lg">
                                                    Подробнее
                                                </a>

                                            </div>
                                            <div class="up-block">
                                                <div class="starts">
                                                    @for($i = 0; $i < 5 ;$i++)
                                                        <div class="item @if($prod->votes !=0) @if($i < (int)($prod->rating_sum / $prod->votes) ) active @endif @endif "></div>
                                                    @endfor
                                                </div>

                                                <div class="characteristics">
                                                    <? $count = 0; ?>

                                                    @foreach(unserialize($prod->specification) as $dtbl)
                                                        @if($count++ > 2) <? break; ?>  @endif
                                                        <div class="item">
                                                            <div class="title">{{ $dtbl['name'] }}</div>
                                                            <div class="value">{{ $dtbl['value'] }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <a class="button" href="/product/{{ $prod->slug }}">
                                                    Подробнее
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach


                            @else
                                <div class="col-xs-12">
                                    <div class="prodEmpty">
                                        Товаров не найдено
                                    </div>
                                </div>
                            @endif

                        </div>

                        @include('frontend._pagination',['paginator' => $products->appends(Request::all())])

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="/frontend/css/contactLenses.css">
    <link rel="stylesheet" href="/frontend/js/slick/slick-theme.css">
    <link rel="stylesheet" href="/frontend/js/slick/slick.css">
@endsection

@section('scripts')
    <!--formStyler-->
    <script type="text/javascript" src="/frontend/js/formStyler/jquery.formstyler.min.js"></script>
    <link rel="stylesheet" href="/frontend/js/formStyler/jquery.formstyler.css">
    <link rel="stylesheet" href="/frontend/js/formStyler/jquery.formstyler.theme.css">

    <!--slick slider-->
    <script src="/frontend/js/slick/slick.min.js"></script>

    <script type="text/javascript" src="/frontend/js/polzunok.js"></script>
    <script type="text/javascript" src="/frontend/js/contactLenses.js"></script>
@endsection