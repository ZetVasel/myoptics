@extends('frontend.layout')

@section('main')

	@include('frontend._breadcrumbs')

	<div class="lensesContentBg">
		<div class="container">
			<div class="row">
				<div class="leftBar col-lg-3">
					<div class="title">Фильтр</div>
					@if( Input::has('filter') )
						<div class="selectedFeatures">


							@foreach( $features as $f )
								@if( isset( Input::get('filter')[$f->id] ) )
									@foreach( $f->options as $key => $o )
										@if( isset( Input::get('filter')[$f->id] )  && in_array( $o->value, Input::get('filter')[$f->id] ) )
											<div class="item">
												<div class="name">{{ $f->name }}</div>
												<div class="value">
													{{ $o->value }}
													<div data-id="{{ $f->id.$o->id }}" class="close delFeature"></div>
												</div>
											</div>

										@endif
									@endforeach
								@endif
							@endforeach

							<a href="#" id="clear_all_filters" class="button">Сбросить всё</a>
						</div>
					@endif


					<form id="filter" method="get">
					<div class="priceChange">
						<div class="priceTitle">
							Цена
						</div>
						<input type="hidden" name="priceMin" id="minCost" value="{{ $cur_price['min'] }}"/>
						<input type="hidden" name="priceMax" id="maxCost" value="{{ $cur_price['max'] }}"/>
						<div id="polzunok"></div>
						<div id="price-range" class="price_range" data-max="{{ $price_range['max']}}"  data-min="{{ $price_range['min']}}"></div>
						<div class="pricesShow">
							<div class="leftPrice"><span>{{ $cur_price['min']}}</span> грн.</div>
							<div class="rightPrice"><span>{{ $cur_price['max']}}</span> грн.</div>
						</div>
					</div>

						@foreach( $features as $f )
							@if( count( $f->options ) )
								<div class="featureBlock">
									<div class="ftitle"><span>{{ $f->name }}</span></div>
									<div class="fcontent">
									@foreach( $f->options as $key => $o )
											<div class="item">
												<input @if( isset( Input::get('filter')[$f->id] )  && in_array( $o->value, Input::get('filter')[$f->id] ) ) checked @endif onchange="this.form.submit()" name="filter[{{ $f->id }}][]" type="checkbox" class="filterCheckbox" value="{{{ $o->value }}}" id="{{ $f->id.$o->id }}">
												<label for="{{ $f->id.$o->id }}">{{ $o->value }} ({{ $o->count }})</label>
											</div>
									@endforeach
											@if(count($f->options) > 4)
												<a data-show="1" href="#" class="button seeMoreFeature">Показать еще</a>
											@endif
									</div>
								</div>
							@endif
						@endforeach
				</form>

				</div>

				<div class="rightBar col-lg-9">
					<div class="row">
						<div class="topSettings col-xs-12">
							<div class="featureButton" id="featureButton">Фильтр</div>
							<div class="sorting">
								<span class="text">Сортировать по:</span>
								<form action="#" method="GET" class="formSort">
									<select onchange="this.form.submit()" name="sorter" id="sort">
										<option  value="-" @if(Input::get('sorter') == '-') selected @endif>-</option>
										<option value="ASC_price" @if(Input::get('sorter') == 'ASC_price') selected @endif>Цена по возрастанию</option>
										<option value="DESC_price" @if(Input::get('sorter') == 'DESC_price') selected @endif>Цена по убыванию</option>
										<option value="ASC_abc" @if(Input::get('sorter') == 'ASC_abc') selected @endif>По алфавиту(от А до Я)</option>
										<option value="ASC_cba" @if(Input::get('sorter') == 'ASC_cba') selected @endif>По алфавиту(от Я до А)</option>
									</select>
								</form>
							</div>

							<div class="showBlock">
								<span class="text">Вид:</span>
								<div class="how">
									<div data-show="1" id="plitka" class="plitka @if(Session::get('show') == 0) active @endif"></div>
									<div data-show="0" id="list" class="list @if(Session::get('show') == 1) active @endif"></div>
								</div>
							</div>

						</div>


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
														{{ $count = 0 }}

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

									<div class="product col-lg-4 col-md-4 col-sm-4 col-xs-12">
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

						@include('frontend._pagination',['paginator'=>$products->appends(Request::all())])

						@if(isset($lastSeenProducts) && count($lastSeenProducts) > 0)

							<div class="lastSeenBlock col-xs-12">
								<div class="ltitle">Вы недавно просматривали</div>

								<div class="lastSeenSlider row">

									@foreach($lastSeenProducts as $item)

										<div class="product col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<div class="item @if($item->discount == 1) discount @endif @if($item->new == 1) new @endif @if($item->sale == 1) sale @endif">
												<div class="image">
													@if( $item->image !='')
														<img src="/uploads/products/md/{{ $item->image }}" alt="{{ $item->name }}">
													@else
														<img src="/uploads/products/md/no_image.png" alt="#">
													@endif
												</div>
												<div class="information">
													<div class="title">{{ $item->name }}</div>
													<div class="text">
														{{ substr(strip_tags($item->description), 0 , 120).'...' }}
													</div>
													<div class="prices">
														@if($item->oldPrice > 0)
															<div class="old">{{ number_format($item->oldPrice, 2, ',', '') }}</div>
														@endif
														<div class="now"><span>{{ number_format($item->price - ($item->price * $userDiscount), 2, ',', '') }}</span> Грн</div>
													</div>

													<a class="button" href="/product/{{ $item->slug }}">
														Подробнее
													</a>

												</div>
												<div class="up-block">
													<div class="starts">
														@for($i = 0; $i < 5 ;$i++)
															<div class="item @if($item->votes != 0) @if($i < (int)($item->rating_sum / $item->votes) ) active @endif @endif "></div>
														@endfor
													</div>

													<div class="characteristics">
                                                        <? $count = 0; ?>

														@foreach(unserialize($item->specification) as $dtbl)
															@if($count++ > 2) <? break; ?>  @endif
															<div class="item">
																<div class="title">{{ $dtbl['name'] }}</div>
																<div class="value">{{ $dtbl['value'] }}</div>
															</div>
														@endforeach
													</div>

													<a class="button" href="/product/{{ $item->slug }}">
														Подробнее
													</a>
												</div>
											</div>
										</div>

									@endforeach


								</div>
								<div class="row">
									<div class="col-xs-12">
										<a href="/category/allseen" id="seeMore" class="seeMore">Просмотреть все</a>
									</div>
								</div>
							</div>

						@endif

					</div>


					<div class="afterBlock">
					</div>


				</div>
			</div>
		</div>
	</div>

@if(Input::get('page') == 1 || !Input::has('page'))

	<div class="container container-body">
		<div class="row">
		<div style="padding-top: 45px; padding-bottom: 50px;">
			<div class="col-xs-12">
				{!! $page->body !!}
			</div>
			</div>
		</div>
	</div>
	@endif
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

	<script>

		$(document).ready(function () {
            heightUp = 0;

            if($(window).width() > 768){
                heightUp = 90;
            }else{
                heightUp = 130;
            }
        });

        $('#plitka').click(function () {
            $('#productGrid').show();
            $('#productList').hide();
            $(this).addClass('active');
            $('#list').removeClass('active');
            $('.rightBar').removeClass('list');
            $('.pagination').removeClass('list');
            $('.afterBlock').height($('#productGrid').height()+ heightUp);

            $.post( '/setShowProducts', { _token: '{{ Session::token() }}', show: 0 });

        });


        $('#list').click(function () {
            $('#productGrid').hide();
            $('#productList').show();
            $(this).addClass('active');
            $('#plitka').removeClass('active');
            $('.rightBar').addClass('list');
            $('.pagination').addClass('list');
            $('.afterBlock').height($('#productList').height()+60);
            $.post( '/setShowProducts', { _token: '{{ Session::token() }}', show: 1 });
        });

        $(document).ready(function () {

            if($(window).width() < 768){
                $('#productGrid').show();
                $('#productList').hide();
            }

			@if(Session::get('show') == 1)
            if($(window).width() > 768) {
                $('.afterBlock').height($('#productList').height() + 60);
            }else{
                $('.afterBlock').height($('#productGrid').height()+ heightUp);
			}

			@else
				 $('.afterBlock').height($('#productGrid').height()+ heightUp);
			@endif
        });

	</script>

<script src="/frontend/js/polzunok.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="/frontend/js/contactLenses.js"></script>
@endsection