@extends('frontend.layout')


@section('main')

    <div class="breadCrumbBg cart">
        <div class="container">
            <div class="row">
                <ul class="breadCrumbs col-xs-12">
                    <li><a href="/">Главная</a></li>
                    <li><a href="/cart">Корзина товаров</a></li>
                    <li><a href="/cartStepTwo">Оформление заказа</a></li>
                    <li><a href="#">{{ $page->name }}</a></li>
                </ul>
                <div class="step col-xs-12">
                    <div class="item cart hidden-xs"><span>Корзина товаров</span></div>
                    <div class="item order hidden-xs"><span>Оформление заказа</span></div>
                    <div class="item confirm active"><span>Подтверджение заказа</span></div>
                </div>
            </div>
        </div>
    </div>




    <div class="cartBg cartThree">
        <div class="container">
            <div class="row">
                <div class="youOrder">
                    <div class="ytitle col-xs-12">
                        <div class="xs-small">
                            <span>Ваш заказ</span> № {{ $countOrder }}
                        </div>
                    </div>


                    <div class="itemsList">

                        @if(count($cartProduct) > 0)
                        {{ $i = 1 }}
                            @foreach($cartProduct as $item)
                                <div class="orderItem col-xs-12">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs">
                                                <div class="num">
                                                    {{ $i++ }}
                                                </div>
                                            </div>

                                            <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
                                                <div class="charList">
                                                    <div class="title">
                                                        {{ $item['product'][0]->name }}
                                                    </div>
                                                    <ul class="charUl">
                                                        @foreach($item['charlist'] as $char)
                                                            <li>{{ $char['name'] }} <span>{{ $char['value'] }}</span></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
                                                <div class="total">
                                                    <div class="count">{{ $item['quantity'] }}</div>
                                                    <div class="price"><span>{{ number_format($item['quantity'] * $item['product'][0]->price - ($item['product'][0]->price * $userDiscount * $item['quantity']), 2, ',', '') }}</span> Грн</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @else

                            Ваша корзина пустая

                        @endif


                    </div>

                </div>



                <div class="youDelivery">
                    <div class="dtitle col-xs-12">Доставка:</div>

                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <div class="deliveryBlock">
                            <div class="ditem">
                                <div class="title">Получатель</div>
                                <div class="value">{{ $name }}</div>
                            </div>

                            <div class="ditem">
                                <div class="title">Телефон</div>
                                <div class="value">{{ $phone }}</div>
                            </div>


                            <div class="ditem address">
                                <div class="title">Адрес доставки</div>
                                <div class="value">г. {{ $city }}, {{ $addres }}</div>
                            </div>

                            <div class="ditem block">
                                <div class="title">Оплата</div>
                                <div class="value">{{ $buy->name }}</div>
                            </div>



                            <div class="ditem block">
                                <div class="title">Комментарий к заказу</div>
                                <div class="value">{{ $coment }}</div>
                            </div>

                        </div>
                    </div>


                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="total">
                            <div class="totalBlock">
                                <div class="title">Итого</div>
                                <div class="titem">
                                    <span class="text">Товары</span>
                                    <span class="value"><b>{{ number_format($cart_info['total_price'],2,',','') }}</b> Грн</span>
                                </div>

                                <div class="titem">
                                    <span class="text">Доставка</span>
                                    <span class="value"><b>{{ number_format($deliveryInfo->price,2,',','') }}</b> Грн</span>
                                </div>

                                <div class="titem">
                                    <span class="text">К оплате</span>
                                    <span class="value"><b>{{ number_format($cart_info['total_price'] + $deliveryInfo->price ,2,',','') }}</b> Грн</span>
                                </div>

                                <form action="/confirmOrder" method="post">

                                    {!! csrf_field() !!}
                                    <input type="hidden" name="name" value="{{$name}}">
                                    <input type="hidden" name="address" value="{{$addres}}">
                                    <input type="hidden" name="phone" value="{{$phone}}">
                                    <input type="hidden" name="city" value="{{$city}}">
                                    <input type="hidden" name="coment" value="{{$coment}}">
                                    <input type="hidden" name="buy" value="{{$buy->id}}">
                                    <input type="hidden" name="delivery" value="{{$deliveryInfo->id}}">
                                    <input type="hidden" name="warehouse" value="{{$warehouseInfo->id}}">
                                    <input type="hidden" name="email" value="{{$mail}}">
                                    <input type="hidden" name="total_cost" value="{{$cart_info['total_price'] + $deliveryInfo->price}}">

                                    <a id="confirmOrder" href="#" class="button">
                                        Подтвердить заказ
                                    </a>
                                </form>
                            </div>

                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>







@endsection

@section('styles')
    <link rel="stylesheet" href="/frontend/css/cart.css">	<link rel="stylesheet" href="/frontend/js/formStyler/jquery.formstyler.css">
    <link rel="stylesheet" href="/frontend/js/formStyler/jquery.formstyler.theme.css">
@endsection

@section('scripts')
    <!--formStyler-->
    <script type="text/javascript" src="/frontend/js/formStyler/jquery.formstyler.min.js"></script>
    <script type="text/javascript" src="/frontend/js/cart.js"></script>

    <script type="text/javascript">
        $('#confirmOrder').click(function (e) {
            e.preventDefault();
           $(this).parent('form').submit();
        });
    </script>

@endsection