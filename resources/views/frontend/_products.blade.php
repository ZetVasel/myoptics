
	@foreach( $products as $prod )
	 <div class="product">
      	<div class="img_block">
      		@if( $prod->hit == 1 )
          		<div class="img_spec"><img src="/frontend/img/sher.png" alt="Акция"></div>
      		@elseif( $prod->new == 1 )
          		<div class="img_spec"><img src="/frontend/img/new.png" alt="Новинка"></div>
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
                  <div class="span">{{$prod->price }}</div>
                  <div style="text-align: center">Вы экономите {{$prod->p_discount }}%</div>
            @endif
        </div>

        
          	<div class="show_compare">
              <div style="display:inline-block; color:#fff; width: 135px; padding-left: 24px;">
                  <span style="padding-top: 15px;display: inline-block;font-size: 24px; vertical-align: top;">
                    <i class="fa fa-balance-scale " aria-hidden="true"></i>
                  </span>
                <span style="display: inline-block;width: 100px;    vertical-align: top;     font-size: 16px;    padding-top: 12px;">
                   @if( Session::has('compare') && in_array( $prod->id, Session::get('compare') ) )
                      Удалить с сравнения 
                    @else
                      Добавить в сравнение
                    @endif
                </span>
              </div>
              <div class="add_compere 
                <!-- @if( Session::has('compare') && in_array( $prod->id, Session::get('compare') ) ) active @endif -->
              " data-product-id="{{ $prod->id }}">+</div>
            </div>
             <div class="price_block">
                @if(  $prod->p_discount > 0  )
                  <div class="price">{{ $prod->price - ( $prod->price * $prod->p_discount/100 ) }} грн 
                    <!-- <span>грн</span> -->
                  </div>
                @else
                  <div class="price">{{ $prod->price }} грн 
                    <!-- <span>грн</span> -->
                  </div>
                @endif
                
              </div>
        <div class="cart_block">
            <div class="to_cart" data-product-id="{{ $prod->id }}" data-token="{{ csrf_token() }}">В корзину</div>
            <div class="bay_click" data-product-id="{{ $prod->id }}" data-token="{{ csrf_token() }}">Купить<br> в один клик</div>
        </div>
  </div>
	@endforeach