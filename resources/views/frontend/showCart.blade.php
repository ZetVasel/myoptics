@extends('frontend.layout')


@section('main')
	<div class="breadCrumbBg cart">
		<div class="container">
			<div class="row">
				<ul class="breadCrumbs col-xs-12">
					<li><a href="/">Главная</a></li>
					<li><a href="/{{ $page->slug }}">{{ $page->name }}</a></li>
				</ul>
				<div class="step col-xs-12">
					<div class="item cart active"><span>Корзина товаров</span></div>
					<div class="item order hidden-xs"><span>Оформление заказа</span></div>
					<div class="item confirm  hidden-xs"><span>Подтверджение заказа</span></div>
				</div>
			</div>
		</div>
	</div>





	<div class="cartBg">
		<div class="container">
			<div class="row">

				@if(count($cartProduct) > 0)

				@foreach($cartProduct as $item)

					<div class="itemContent col-xs-12">
						<div class="item">
							<form action="/remove_from_cart" method="post">
								{!! csrf_field() !!}
								<input type="hidden" name="arrkey" value="{{ $item['arrKey'] }}">
								<div class="delete"></div>
							</form>
							<div class="row">
								<div class="title col-lg-12 col-md-12 col-sm-12 col-xs-10">{{ $item['product'][0]->name }}</div>
								<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
									<div class="image">
										@if( $item['product'][0]->image !='')
											<img src="/uploads/products/sm/{{ $item['product'][0]->image }}" alt="{{ $item['product'][0]->name }}">
										@else
											<img src="/uploads/products/sm/no_image.png" alt="#">
										@endif
									</div>
								</div>

								<div class="information col-lg-7 col-md-9 col-sm-9 col-xs-12">
									<ul class="charList">
										@foreach($item['charlist'] as $char)
											<li>{{ $char['name'] }} <span>{{ $char['value'] }}</span></li>
										@endforeach
									</ul>
									<div data-arrkey="{{ $item['arrKey'] }}" data-name="{{ $item['product'][0]->name }}" data-id="{{ $item['product'][0]->id }}" class="changeChar">Изменить параметры</div>
								</div>


								<div class="col-lg-3 col-lg-offset-0 col-md-offset-7 col-sm-offset-7 col-md-5 col-sm-5 col-xs-12 col-xs-offset-0">
									<div class="action">
										<div class="countBlock">
											<form action="/changeQuantity" method="post">
												{!! csrf_field() !!}
												<input type="hidden" name="product" value="{{ $item['product'][0]->id }}">
												<input type="hidden" name="arrkey" value="{{ $item['arrKey'] }}">
												<input type="hidden" name="quantity" value="{{ $item['quantity'] }}">
												<div class="minus">-</div>
												<div class="total">{{ $item['quantity'] }}</div>
												<div class="plus">+</div>
											</form>
										</div>
										<div class="price">
											<span>{{ number_format($item['quantity'] * ($item['product'][0]->price - ($item['product'][0]->price * $userDiscount)), 2, ',', '') }}</span> Грн
										</div>
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
	</div>



	<div class="cartBottomBg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="cartBottom">
						<a href="/category" class="back">
							Продолжить покупки
						</a>
						@if(count($cartProduct) > 0)
							<a href="/cartStepTwo" class="continue">Оформить заказ</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>



	<div class="cartChangeForm" id="cartChangeForm">
		<div id="closeChangeForm"></div>
		<div class="title"></div>
		<div class="featureList">
			<form action="/newFeature" method="post">
				{!! csrf_field() !!}
				<input type="hidden" name="arrkey" value="">
				<input type="hidden" name="product_id" value="">
				<div id="body"></div>
			</form>
		</div>

		<a href="#" id="changeFeature" class="button">Сохранить</a>

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


        function setCount(action , obj) {
            var total = +obj.parent().find('.total').text();
            var form = $(obj).parent('form');
            //todo
            switch (action){
                case 'minus':
                    if(total - 1 > 0) { obj.parent().find('.total').text(total - 1); form.find('input[name="quantity"]').val(total - 1) }

                    break;
                case 'plus':
                    obj.parent().find('.total').text(total + 1);
                    form.find('input[name="quantity"]').val(total + 1);
                break;
            }
            form.submit();
        }

        $('.minus').click(function () {
            setCount('minus', $(this));
        });
        $('.plus').click(function () {
            setCount('plus', $(this));
        });
        $('.delete').click(function () {
           $(this).parent('form').submit();
        });


        $('.changeChar').click(function () {
            $('#cartChangeForm').fadeToggle('slow');
            $('.overlay').fadeToggle('fast');

            var product_id = +$(this).data('id');
			var arrkey = $(this).data('arrkey');
			var name = $(this).data('name');
            $('#body').parent('form').find('input[name="arrkey"]').val(arrkey);
            $('#body').parent('form').find('input[name="product_id"]').val(product_id);


            $('#body').parent().parent().parent().find('.title').text(name);
            $.post( '/getCharList', { _token: '{{ Session::token() }}', product_id: product_id }, // сдесь был убран _token: '{{ Session::token() }}', из-за ошибки при добавлений товара в корз
			function (data) {
				$('#body').html(data);
                $('.featureSelect').styler();
            }
			);


        });

        $('#closeChangeForm').click(function () {
            $('#cartChangeForm').fadeOut('slow');
            $('.overlay').fadeOut('fast');
        });

        $('#changeFeature').click(function () {
			$(this).parent().find('form').submit();
        });


	</script>


@endsection