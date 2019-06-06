@extends('frontend.layout')

@section('main')
    <div class="slider-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="sliderBg">
                        <div class="homeSlider">

                            @foreach( $slider as $slide )
                                <div class="item">
                                    <div class="image">
                                        <img src="/uploads/slides/{{ $slide->image }}" alt="#">
                                    </div>

                                    <div class="rightSliderText">
                                        <div class="title">{{ $slide->title }}</div>
                                        <div class="text">
                                            {{ $slide->text }}
                                        </div>
                                        <a href="{{$slide->link}}" class="more"> @if(empty($slide->textButton)) Подробнее @else {{ $slide->textButton }} @endif</a>
                                    </div>
                               </div>

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-bg">
        @if($catBlock1)

        <div class="container">
            <div class="row">

                <div class="items-block @if($catBlock1->gradient == 'green') green @endif @if($catBlock1->gradient == 'yellow') yellow @endif col-xs-12">
                    <div class="row">
                        <div class="catBlock col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="category">
                                <img src="/uploads/category/<?=!empty($catBlock1->image) ? $catBlock1->image : 'no_image.png'?>" alt="#">
                                <div class="blockInfo">
                                    <div class="title">{{ $catBlock1->name }}</div>
                                    <div class="description">{{ substr(strip_tags($catBlock1->body), 0 , 180).'...' }}</div>
                                    <div class="button"><a href="/category/{{ $catBlock1->slug }}">Перейти в каталог</a></div>
                                </div>
                            </div>
                        </div>

                        <div class="products-slider col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                @foreach($catBlock1Products as $catBlock1Product)

                                    <div class="product col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="item  @if($catBlock1Product->discount == 1) discount @endif @if($catBlock1Product->new == 1) new @endif @if($catBlock1Product->sale == 1) sale @endif ">
                                            <div class="image">
                                                @if( $catBlock1Product->image !='')
                                                    <img src="/uploads/products/md/{{ $catBlock1Product->image }}" alt="{{ $catBlock1Product->name }}">
                                                @else
                                                    <img src="/uploads/products/md/no_image.png" alt="#">
                                                @endif
                                            </div>
                                            <div class="information">
                                                <div class="title">{{ $catBlock1Product->name }}</div>
                                                <div class="text">
                                                    {{ substr(strip_tags($catBlock1Product->description), 0 , 120).'...' }}
                                                </div>
                                                <div class="prices">
                                                    @if($catBlock1Product->oldPrice > 0)
                                                        <div class="old">{{ number_format($catBlock1Product->oldPrice, 2, ',', '') }}</div>
                                                    @endif
                                                    <div class="now"><span>{{ number_format($catBlock1Product->price - ($catBlock1Product->price * $userDiscount), 2, ',', '') }}</span> Грн</div>
                                                </div>
                                            </div>

                                            <div class="up-block">
                                                <div class="starts">
                                                    @for($i = 0; $i < 5 ;$i++)
                                                        <div class="item @if($catBlock1Product->votes !=0) @if($i < (int)($catBlock1Product->rating_sum / $catBlock1Product->votes) ) active @endif @endif"></div>
                                                    @endfor
                                                </div>

                                                <div class="characteristics">

                                                    <? $count = 0; ?>

                                                        @foreach(unserialize($catBlock1Product->specification) as $dtbl)
                                                            @if($count++ > 2) <? break; ?>  @endif
                                                            <div class="item">
                                                                <div class="title">{{ $dtbl['name'] }}</div>
                                                                <div class="value">{{ $dtbl['value'] }}</div>
                                                            </div>
                                                        @endforeach

                                                </div>

                                                <a class="button" href="/product/{{ $catBlock1Product->slug }}">
                                                    Подробнее
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                        @endforeach

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endif

      @if($catBlock2)
        <div class="container">
            <div class="row">
                <div class="items-block @if($catBlock2->gradient == 'green') green @endif @if($catBlock2->gradient == 'yellow') yellow @endif col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div id="toGreenCatSm"></div>
                        <div class="products-slider col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">

                                @foreach($catBlock2Products as $catBlock2Product)

                                        <div class="product col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="item @if($catBlock2Product->discount == 1) discount @endif @if($catBlock2Product->new == 1) new @endif @if($catBlock2Product->sale == 1) sale @endif">
                                                <div class="image">
                                                    @if( $catBlock2Product->image !='')
                                                        <img src="/uploads/products/md/{{ $catBlock2Product->image }}" alt="{{ $catBlock2Product->name }}">
                                                    @else
                                                        <img src="/uploads/products/md/no_image.png" alt="#">
                                                    @endif
                                                </div>
                                                <div class="information">
                                                    <div class="title">{{ $catBlock2Product->name }}</div>
                                                    <div class="text">
                                                        {{ substr(strip_tags($catBlock2Product->description), 0 , 120).'...' }}
                                                    </div>
                                                    <div class="prices">
                                                        @if($catBlock2Product->oldPrice > 0)
                                                            <div class="old">{{ number_format($catBlock2Product->oldPrice, 2, ',', '') }}</div>
                                                        @endif
                                                        <div class="now"><span>{{ number_format($catBlock2Product->price - ($catBlock2Product->price * $userDiscount), 2, ',', '') }}</span> Грн</div>
                                                    </div>
                                                </div>

                                                <div class="up-block">
                                                    <div class="starts">
                                                        @for($i = 0; $i < 5 ;$i++)
                                                            <div class="item @if($catBlock2Product->votes !=0) @if($i < (int)($catBlock2Product->rating_sum / $catBlock2Product->votes) ) active @endif @endif "></div>
                                                        @endfor
                                                    </div>

                                                    <div class="characteristics">
                                                        {{ $count = 0 }}

                                                        @foreach(unserialize($catBlock2Product->specification) as $dtbl)
                                                            @if($count++ > 2) <? break; ?>  @endif
                                                            <div class="item">
                                                                <div class="title">{{ $dtbl['name'] }}</div>
                                                                <div class="value">{{ $dtbl['value'] }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <a class="button" href="/product/{{ $catBlock2Product->slug }}">
                                                        Подробнее
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                            </div>
                        </div>

                        <div class="catBlock col-lg-3 col-md-12 col-sm-12 col-xs-12" id="greenCat">
                            <div class="category">
                                <img src="/uploads/category/<?=!empty($catBlock2->image) ? $catBlock2->image : 'no_image.png'?>" alt="#">
                                <div class="blockInfo">
                                    <div class="title">{{ $catBlock2->name }}</div>
                                    <div class="description">{{ substr(strip_tags($catBlock2->body), 0 , 180).'...' }}</div>
                                    <div class="button"><a href="/category/{{ $catBlock2->slug }}">Перейти в каталог</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            @endif
            @if($catBlock3)

        <div class="container">
            <div class="row">
                <div class="items-block @if($catBlock3->gradient == 'green') green @endif @if($catBlock3->gradient == 'yellow') yellow @endif col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">

                        <div class="catBlock col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="category">
                                <img src="/uploads/category/<?=!empty($catBlock3->image) ? $catBlock3->image : 'no_image.png'?>" alt="#">
                                <div class="blockInfo">
                                    <div class="title">{{ $catBlock3->name }}</div>
                                    <div class="description">{{ substr(strip_tags($catBlock3->body), 0 , 145).'...' }}</div>
                                    <div class="button"><a href="/category/{{ $catBlock3->slug }}">Перейти в каталог</a></div>
                                </div>
                            </div>
                        </div>

                        <div class="products-slider col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">


                                @foreach($catBlock3Products as $catBlock3Product)

                                <div class="product col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="item @if($catBlock3Product->discount == 1) discount @endif @if($catBlock3Product->new == 1) new @endif @if($catBlock3Product->sale == 1) sale @endif">
                                        <div class="image">
                                            @if( $catBlock3Product->image !='')
                                                <img src="/uploads/products/md/{{ $catBlock3Product->image }}" alt="{{ $catBlock3Product->name }}">
                                            @else
                                                <img src="/uploads/products/md/no_image.png" alt="#">
                                            @endif
                                        </div>
                                        <div class="information">
                                            <div class="title">{{ $catBlock3Product->name }}</div>
                                            <div class="text">
                                                {{ substr(strip_tags($catBlock3Product->description), 0 , 120).'...' }}
                                            </div>
                                            <div class="prices">
                                                @if($catBlock3Product->oldPrice > 0)
                                                    <div class="old">{{ number_format($catBlock3Product->oldPrice, 2, ',', '') }}</div>
                                                @endif

                                                <div class="now"><span>{{ number_format($catBlock3Product->price - ($catBlock3Product->price * $userDiscount), 2, ',', '') }}</span> Грн</div>
                                            </div>
                                        </div>


                                        <div class="up-block">
                                            <div class="starts">
                                                @for($i = 0; $i < 5 ;$i++)
                                                    <div class="item @if($catBlock3Product->votes != 0) @if($i < (int)($catBlock3Product->rating_sum / $catBlock3Product->votes) ) active @endif @endif "></div>
                                                @endfor
                                            </div>

                                            <div class="characteristics">
                                                <? $count = 0; ?>

                                                @foreach(unserialize($catBlock3Product->specification) as $dtbl)
                                                    @if($count++ > 2) <? break; ?>  @endif
                                                    <div class="item">
                                                        <div class="title">{{ $dtbl['name'] }}</div>
                                                        <div class="value">{{ $dtbl['value'] }}</div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <a class="button" href="/product/{{ $catBlock3Product->slug }}">
                                                Подробнее
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                    @endforeach

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    </div>


    <div class="brandsBg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="brandsSlider">

                            @foreach($brands as $brand)

                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="item">
                                    <a href="/brand/{{ $brand->slug }}">
                                        <img src="uploads/brands/{{ $brand->image }}" alt="{{ $brand->name }}">
                                    </a>
                                </div>
                            </div>
                          @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="newsBg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="newsSlider">

                            @foreach($news as $item)

                                <?

                                    $date = explode(' ',$item->created_at);
                                    $date = explode('-', $date[0]);

                                    $year = $date[0];
                                    $mounth = $date[1];
                                    $day = $date[2];

                                ?>

                                <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                    <div class="slide">
                                        <div class="image">
                                            <div class="smParentImage">
                                                <div class="date">
                                                    <div class="number"><? echo $day ?></div>
                                                    <div class="mounth"><? echo $mounth ?>/<? echo $year ?></div>
                                                </div>
                                                <img src="/uploads/news/<?=!empty($item->image) ? $item->image : 'no_image.png'?>" alt="#">
                                            </div>
                                        </div>
                                        <div class="news-content">
                                            <div class="content">
                                                <div class="title">
                                                    {{ $item->name }}
                                                </div>
                                                <div class="text">
                                                    {{ substr(strip_tags($item->body), 0 , 180).'...' }}
                                                </div>
                                                <a href="/news/{{ $item->slug }}" class="more">Подробнее</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                        </div>
                        <a class="moreNews" href="/news">Больше новостей</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container container-body">
        <div class="row">
            <div class="col-xs-12">
                {!! $page->body !!}
            </div>
        </div>
    </div>

@endsection

@section('styles')
 <!--slick slider-->
 <link rel="stylesheet" href="/frontend/js/slick/slick-theme.css">
 <link rel="stylesheet" href="/frontend/js/slick/slick.css">

{{--home--}}
 <link rel="stylesheet" href="/frontend/css/home.css">
@endsection

@section('scripts')
    <!--slick slider-->
    <script src="/frontend/js/slick/slick.min.js"></script>
    <script src="/frontend/js/home.js"></script>

    <script>
        $(document).ready(function () {
            $('.homeSlider').slick({
                dots: false
                @if($slideChangeTime > 0)
                ,
                autoplay: true,
                autoplaySpeed: {{ $slideChangeTime }}
                @endif
            });
        });
    </script>

@endsection