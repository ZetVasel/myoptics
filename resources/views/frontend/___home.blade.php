@extends('frontend.layout')




@section('main')
<div class="order_popup">
      <form id="quick_order_form" method="POST" data-token="{{ csrf_token() }}">
        <div href="#" class="close close_popup">X</div>
        <p class="title">Заказ в один клик</p>
        <input type="text" name="name" placeholder="Ваше имя" required>
        <input type="text" name="phone" placeholder="Ваш номер телефона" required pattern="^((8|\+3)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$" title="Номер должен быть в таком формате: 0997788666">
        <input type="submit" class="btn" value="Заказать">
      </form>
        <p class="msg"></p>
</div>
            <div class="slider">
                <div class="slide_block">
                    <ul class="bxslider">
                        @foreach( $slider as $slid )
                            <li>
                                <img src="/uploads/slides/{{ $slid->image }}" alt="{{ $slid->title }}">
                                <div class="text_slider_block">
                                    <div class="title">{{ $slid->title }}</div>
                                    <div class="text">{{ $slid->text }}</div>
                                    <div class="triangle"><img src="/frontend/img/tr.png" alt="#"></div>
                                </div>
                            </li>   
                        @endforeach
                    </ul>
                </div>
                <div class="baner_slid">
                    <img src="/uploads/slides/{{$settings->baner}}" alt="#">
                    <!-- <img src="/frontend/img/baner_slud.jpg" alt="1111111111"> -->
                </div>
            </div>
            <div class="main_product_block">
              @if( count( $products_hit )> 0)
                <div class="title">Хиты продаж</div>
                <div class="section_product_block">
                    <ul class="bxslider2">
                          @foreach( $products_hit as $hit)
                            <li>
                              <div class="product">
                                  <div class="img_block">
                                      <div class="img_spec"><img src="/frontend/img/sher.png" alt="#"></div>
                                      <a href="/product/{{ $hit->slug }}">
                                        @if( $hit->image !='')
                                          <img src="/uploads/products/sm/{{ $hit->image }}" alt="{{ $hit->name }}">
                                        @else
                                          <img src="/frontend/img/no_image.jpg" alt="#">
                                        @endif
                                      </a>
                                  </div>
                                  <div class="title_product"><a href="/product/{{ $hit->slug }}">{{ $hit->name }}</a></div>
                                    <div class="discount">
                                        @if(  $hit->p_discount > 0  )
                                              <div class="span">{{$hit->price }}</div>
                                              <div style="text-align: center">Вы экономите {{$hit->p_discount }}%</div>
                                        @endif
                                    </div>
                                    <div class="show_compare">
                                      <div style="display:inline-block; color:#fff; width: 135px; padding-left: 24px;">
                                          <span style="padding-top: 15px;display: inline-block;font-size: 24px; vertical-align: top;">
                                            <i class="fa fa-balance-scale " aria-hidden="true"></i>
                                          </span>
                                        <span style="display: inline-block;width: 100px;    vertical-align: top;     font-size: 16px;    padding-top: 12px;">
                                           @if( Session::has('compare') && in_array( $hit->id, Session::get('compare') ) )
                                              Удалить с сравнения 
                                            @else
                                              Добавить в сравнение
                                            @endif
                                        </span>
                                      </div>
                                      <div class="add_compere 
                                        <!-- @if( Session::has('compare') && in_array( $hit->id, Session::get('compare') ) ) active @endif -->
                                      " data-product-id="{{ $hit->id }}">+</div>
                                    </div>
                                  <div class="price_block">
                                    @if(  $hit->p_discount > 0  )
                                      <div class="price">{{ $hit->price - ( $hit->price * $hit->p_discount/100 ) }} <span>грн</span></div>
                                    @else
                                      <div class="price">{{ $hit->price }} <span>грн</span></div>
                                    @endif
                                    
                                      <!-- <div class="compare">
                                          <i class="fa fa-balance-scale " aria-hidden="true"></i>
                                          <div class="add_compere @if( Session::has('compare') && in_array( $hit->id, Session::get('compare') ) ) active @endif" data-product-id="{{ $hit->id }}"></div>
                                      </div> -->

                                  </div>
                                  <div class="cart_block">
                                      <div class="to_cart" data-product-id="{{ $hit->id }}" data-token="{{ csrf_token() }}">В корзину</div>
                                      <div class="bay_click" data-product-id="{{ $hit->id }}" data-token="{{ csrf_token() }}">Купить<br> в один клик</div>
                                  </div>
                              </div>
                           </li>
                          @endforeach
                    </ul>
                </div>
                @endif
                @if( count( $products_new )> 0)
                <div class="title">Новинки</div>
                <div class="section_product_block">
                    <ul class="bxslider3">
                        @foreach( $products_new as $hit)
                            <li>
                              <div class="product">
                                  <div class="img_block">
                                      <div class="img_spec"><img src="/frontend/img/new.png" alt="#"></div>
                                      <a href="/product/{{ $hit->slug }}">
                                        @if( $hit->image !='')
                                          <img src="/uploads/products/sm/{{ $hit->image }}" alt="{{ $hit->name }}">
                                        @else
                                          <img src="/frontend/img/no_image.jpg" alt="#">
                                        @endif
                                      </a>
                                  </div>
                                  <div class="title_product"><a href="/product/{{ $hit->slug }}">{{ $hit->name }}</a></div>
                                   
                                    <div class="show_compare">
                                      <div style="display:inline-block; color:#fff; width: 135px; padding-left: 24px;">
                                          <span style="padding-top: 15px;display: inline-block;font-size: 24px; vertical-align: top;">
                                            <i class="fa fa-balance-scale " aria-hidden="true"></i>
                                          </span>
                                        <span style="display: inline-block;width: 100px;    vertical-align: top;     font-size: 16px;    padding-top: 12px;">
                                           @if( Session::has('compare') && in_array( $hit->id, Session::get('compare') ) )
                                              Удалить с сравнения 
                                            @else
                                              Добавить в сравнение
                                            @endif
                                        </span>
                                      </div>
                                      <div class="add_compere 
                                        <!-- @if( Session::has('compare') && in_array( $hit->id, Session::get('compare') ) ) active @endif -->
                                      " data-product-id="{{ $hit->id }}">+</div>
                                    </div>
                                  <div class="price_block">
                                    @if(  $hit->p_discount > 0  )
                                      <div class="price">{{ $hit->price - ( $hit->price * $hit->p_discount/100 ) }} <span>грн</span></div>
                                    @else
                                      <div class="price">{{ $hit->price }} <span>грн</span></div>
                                    @endif
                                     <div class="discount">
                                        @if(  $hit->p_discount > 0  )
                                        <div class="span">{{$hit->price }}</div>
                                        <div>Вы экономите {{$hit->p_discount }}%</div>
                                        @endif
                                    </div>
                                      
                                    
                                        
                                      <!-- <div class="compare"> -->
                                          <!-- <i class="fa fa-balance-scale " aria-hidden="true"></i> -->
                                          
                                      <!-- </div> -->
                                    <div class="clear"></div>
                                  </div>
                                  <div class="cart_block">
                                      <div class="to_cart" data-product-id="{{ $hit->id }}" data-token="{{ csrf_token() }}">В корзину</div>
                                      <div class="bay_click" data-product-id="{{ $hit->id }}" data-token="{{ csrf_token() }}">Купить<br> в один клик</div>
                                  </div>
                              </div>
                           </li>
                          @endforeach
                    </ul>
                </div>
           @endif
          </div>
          <div class="brend_block">
              <div class="title_page">Бренды</div>
              <ul class="brendsslider">
                @foreach( $brands as $brend )
                  <li><a href="#"><img src="/uploads/brands/{{ $brend->image }}" alt="{{ $brend->name }}"></a></li>

                @endforeach
                  
              </ul>
          </div>
          <div class="main_page_text">
            {!! $page->body !!}
          </div>
          <div class="news_block" style="font-size: 0px;">
            <div class="title_page">Новости</div>
            @foreach( $news as $n )
                <div class="news">
                    <div class="img_news">
                    <div class="bg_show"></div>
                        <img src="uploads/news/{{ $n->image }}" alt="{{ $n->name }}">
                    </div>
                    <div class="descript">
                        <div class="title_news"><a href="/news/{{ $n->slug }}">{{ $n->name }}</a></div>
                        <div class="create_date">{{ $n->created_at->format('m/d/Y') }}</div>
                    </div>
                </div>
            @endforeach
              <div class="more_news"><a href="/news">Больше новостей</a></div>
          </div>
@endsection

@section('styles')
 <link rel="stylesheet" href="/frontend/css/jquery.bxslider.css">
 
@endsection



@section('scripts')
<script src='/frontend/js/jquery.bxslider.js'></script>
<script type="text/javascript">

//Добавить в корзину
$('.to_cart').click(function(){
    var product_id = $(this).data('productId');
    console.log(product_id);
    $.post('/tocart', {_token: $(this).data('token'), product_id: product_id },
    function(data){
      $('.overlay').show();
      $('.popup-cart').fadeIn(300);
     
    });
    return false;
});

// добавить в сравнение
  $('.product').on('click','.add_compere', function(){
    var product_id = $(this).data('productId');
    console.log(product_id);
    // return false;
    $.post("/to_compare", { product_id:product_id, _token: '{{ csrf_token() }}' }, function(data){

    });
    $(this).toggleClass('active');
    return false;
  });

// купить в один клик
$('.bay_click').click(function(){

    product_id = $(this).data('productId');
    $('.overlay').show();
    $('.order_popup').fadeIn(500);
    $('#quick_order_form').show();
    $('.order_popup p.msg').empty();
    return false;
});


// отправляет аяксом нужную информацию для быстрого заказа
$('#quick_order_form').submit(function(e){
  e.preventDefault();
    var _token = $(this).data('token');
    var name = $(this).find("input[name='name']").val();
    var phone = $(this).find("input[name='phone']").val();
    product_id: product_id;
    $.post('/quick_order', { _token:_token, product_id: product_id, name:name, phone:phone }, function(data){
      $('#quick_order_form').hide();
      if( data == 1 ){
        $('.order_popup p.msg').text('Ваш заказ успешно оформлен. Мы с Вами свяжемся в ближайшее время. Спасибо!');
      }
      else{
        $('.order_popup p.msg').text('Произошла ошибка. Сообщите нам об ошибке')
      }
  });
});


$(document).ready(function(){
    $('.bxslider2').bxSlider({
        slideWidth: 255,
        minSlides: 1,
        maxSlides: 4,
        moveSlides: 1,
        slideMargin: 29,
        pager: false,
     });
    $('.bxslider3').bxSlider({
        slideWidth: 255,
        minSlides: 1,
        maxSlides: 4,
        moveSlides: 1,
        slideMargin: 29,
        pager: false,
     });
    $('.bxslider').bxSlider({
        pager: true,
        slideMargin: 10,
        // controls: true,
    });
    $('.brendsslider').bxSlider({
        slideWidth: 170,
        minSlides: 1,
        maxSlides: 5,
        moveSlides: 1,
        slideMargin: 29,
        pager: false,
    });
});
</script>	
@endsection