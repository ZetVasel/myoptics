@extends('frontend.layout')

@section('main')
<div class="page-container">
	<div class="h1"><div>{{ $page->name }}</div></div>
	<div class="wrapper wr-945">
		<div class="wrapper">
			<div class="cleargix">
				@include('frontend._products')
			</div>		
		</div>
	</div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">
	$('a.close').on('click',  function(){
		console.log(2);
		var pr_id = $(this).closest('li').data('productId');
		$.post('/favorites', {_token: '{{ csrf_token() }}', pr_id: pr_id }, function(data){
			location.reload();
		});
		return false;
	});
</script>

@endsection