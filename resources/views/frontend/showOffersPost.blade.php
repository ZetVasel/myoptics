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
<div class="breadcrumbs wrapper" style="font-size: 14px">
    <a href="/">Главная</a>
  <span> / </span> <a href="/offers">Скидки и акции</a>
  <span> / </span> <a href="/offers/{{ $page->slug }}">{{ $page->name }}</a>
</div>
	<div class="h1">{{ $page->name }}</div>
	<div class="offers">
		@foreach( $products as $prod)
		    <div class="product">
		          <div class="img_block">
                  @if( $prod->hit == 1)
                    <div class="img_spec"><img src="/frontend/img/sher.png" alt="#"></div>
                  @elseif( $prod->new == 1)
                    <div class="img_spec"><img src="/frontend/img/new.png" alt="#"></div>
                  @endif
                  <a href="/product/{{ $prod->slug }}">
                    @if( $prod->image !='')
                      <img src="/uploads/products/sm/{{ $prod->image }}" alt="{{ $prod->name }}">
                    @else
                      <img src="/frontend/img/no_image.jpg" alt="#">
                    @endif
                  </a>
              </div>
		          <div class="title_product"><a href="/product/{{ $prod->slug }}">{{ $prod->name }}</a></div>
			            <div class="discount">
		          			@if(  $prod->p_discount > 0  )
				                <span>{{$prod->price }}</span>
				                Вы экономите {{$prod->p_discount }}%
		          			@endif
			            </div>
		      	<div class="price_block">
		            @if(  $prod->p_discount > 0  )
		              <div class="price">{{ $prod->price - ( $prod->price * $prod->p_discount/100 ) }} <span>грн</span></div>
		            @else
		              <div class="price">{{ $prod->price }} <span>грн</span></div>
		            @endif
		            
		            <div class="compare">
		                <i class="fa fa-balance-scale " aria-hidden="true"></i>
		                <div class="add_compere @if( Session::has('compare') && in_array( $prod->id, Session::get('compare') ) ) active @endif" data-product-id="{{ $prod->id }}"></div>
		            </div>
		      	</div>
		        <div class="cart_block">
		              <div class="to_cart" data-product-id="{{ $prod->id }}" data-token="{{ csrf_token() }}">В корзину</div>
		              <div class="bay_click" data-product-id="{{ $prod->id }}" data-token="{{ csrf_token() }}">Купить<br> в один клик</div>
		        </div>
		    </div>
		@endforeach
    {!! $page->body !!}
	</div>
@endsection

@section('scripts')
<script>	
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
  $('.compare').on('click','.add_compere', function(){
    var product_id = $(this).data('productId');
    console.log(product_id);
    $.post("/to_compare", { product_id:product_id, _token: '{{ csrf_token() }}' }, function(data){

    });
    $(this).addClass('active');
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
</script>	

@endsection