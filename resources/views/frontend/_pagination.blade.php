@if ($paginator->lastPage() > 1)

	<? $lastShowPage = 0; ?>

	<ul class="pagination col-xs-12">
		@for ($i = 1; $i <= $paginator->lastPage(); $i++)
			@if( abs($paginator->currentPage() - $i) <= 2 )
				<li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
					<a href="{{ $paginator->url($i) }}">{{ $i }}</a>
					{{ $lastShowPage = $i }}
				</li>
			@endif
		@endfor
		@if($paginator->lastPage() - $paginator->currentPage() > 3)
			<li><a href="#">...</a></li>
		@endif
		@if($paginator->lastPage() != $paginator->currentPage() && $lastShowPage != $paginator->lastPage())
			<li><a href="{{ $paginator->url($paginator->lastPage()) }}" >{{ $paginator->lastPage() }}</a> </li>
		@endif

	</ul>

@endif