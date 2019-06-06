@extends('frontend.layout')
@section('main')

    <div class="breadCrumbBg contacts">
        <div class="container">
            <div class="row">
                <ul class="breadCrumbs col-xs-12">
                    <li><a href="/">Главная</a></li>
                    @if( Request::segment(1) =='news' )
                        <li><a href="/news">Новости</a></li>
                        <li><a href="/news/{{ $page->slug }}">{{ $page->name }}</a></li>
                    @elseif( Request::segment(1) =='brand' )
                        <li> <a href="/brand/{{ $page->slug }}">{{ $page->name }}</a></li>
                    @else
                        <li><a href="/{{ $page->slug }}">{{ $page->name }}</a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>


    <div id="map" class="map"></div>

    <div class="contactsBg">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="leftContact">
                        <div class="title">Контакты</div>
                        @if(!empty($settings->phone1))
                            <div class="phone">{{ $settings->phone1 }}</div>
                        @endif

                        @if(!empty($settings->phone2))
                            <div class="phone">{{ $settings->phone2 }}</div>
                        @endif

                        @if(!empty($settings->email))
                            <div class="email">{{ $settings->email }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                    <div class="addressBlock">
                        <div class="title">Наша сеть магазинов</div>
                        <div class="address">

                            @foreach($firstBlockContent as $item)

                                <div data-id="{{ $item->key }}" class="item">
                                    <span>{{ $item->address }}</span>
                                </div>

                            @endforeach

                        </div>
                        <div class="address">
                            @foreach($secondBlockContent as $item)

                                <div data-id="{{ $item->key }}" class="item">
                                    <span>{{ $item->address }}</span>
                                </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                {!! $page->body !!}
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <link rel="stylesheet" href="/frontend/css/contacts.css">
@endsection


@section('scripts')
    <!--api google maps-->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyBSQ__lQxAV6160JpuFY-MvwV8ZvCpPyqQ"></script>


    <script type="text/javascript">
        var point = [];

        @foreach($pointers as $key => $pointer)
            point[{{$key}}] = new google.maps.LatLng({{ $pointer->latitude }}, {{ $pointer->longitude }});
        @endforeach

        var myMapOptions = {
            zoom: 12,
            center: point[0],
            mapTypeId: google.maps.MapTypeId.TERRAIN
        };

        var map = new google.maps.Map(document.getElementById("map"),myMapOptions);

        var image = new google.maps.MarkerImage(
            'frontend/img/marker.png',
            new google.maps.Size(22,33),
            new google.maps.Point(0,0),
            new google.maps.Point(11,33)
        );


        var shape = {
            coord: [13,0,15,1,16,2,17,3,18,4,19,5,20,6,20,7,21,8,21,9,21,10,21,11,21,12,21,13,21,14,21,15,20,16,20,17,20,18,19,19,19,20,18,21,18,22,17,23,17,24,16,25,16,26,15,27,15,28,14,29,14,30,13,31,12,32,11,32,10,31,9,30,8,29,7,28,7,27,6,26,6,25,5,24,5,23,4,22,4,21,3,20,3,19,2,18,2,17,2,16,1,15,1,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,1,5,1,4,2,3,3,2,4,1,6,0,13,0],
            type: 'poly'
        };

        for(var i = 0; i < point.length; i++){
            new google.maps.Marker({
                draggable: false,
                raiseOnDrag: false,
                icon: image,
                shadow: false,
                shape: shape,
                map: map,
                position: point[i]
            });
        }


        $('.address .item span').click(function () {
            var index = $(this).parent('.item').data('id');
            map.setCenter(point[index]);
            var i = 12;
            (function() {
                if (i < 17) {
                    map.setZoom(i++);
                    setTimeout(arguments.callee, 85);
                }
            })();
            if($(window).width() < 768){
                $('html, body').animate({scrollTop: 0},500);
            }
        });



    </script>

@endsection