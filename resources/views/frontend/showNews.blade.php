@extends('frontend.layout')

@section('main')
	<div class="breadCrumbBg news">
		<div class="container">
			<div class="row">
				<ul class="breadCrumbs col-xs-12">
					<li><a href="/">Главная</a></li>
					<li><a href="/{{ $page->slug }}">{{ $page->name }}</a></li>
				</ul>
				<div class="title col-xs-12">
					Статьи
				</div>
			</div>
		</div>
	</div>

	<div class="newsContentBg">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">

					@if( count($news) )
						@foreach( $news as $n )

                            <?
                            $date = explode(' ',$n->created_at);
                            $date = explode('-', $date[0]);

                            $year = $date[0];
                            $mounth = $date[1];
                            $day = $date[2];
                            ?>

							<div class="item">
								<div class="leftImage">
									<div class="date">
										<div class="number">{{ $day }}</div>
										<div class="mounth">{{ $mounth }}/{{ $year }}</div>
									</div>
									<img src="/uploads/news/<?=!empty($n->image) ? $n->image : 'no_image.png'?>" alt="#">
								</div>
								<div class="rightBlock">
									<div class="content">
										<div class="title">{{ $n->name }}</div>
										<div class="text">
											<div class="descr">
												{{ substr(strip_tags($n->body), 0 , 580)}}
											</div>
										</div>
										<a href="/news/{{ $n->slug }}" class="more">Подробнее</a>
									</div>
								</div>
							</div>

						@endforeach
					@else
						<p class="empty_query">На данный момент новости отсутствуют</p>
					@endif

					<div class="newsPaginationBg">
						<div class="row">
							@include('frontend._pagination',['paginator'=>$news->appends(Request::all())])
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
	<link rel="stylesheet" href="/frontend/css/news.css">
@endsection