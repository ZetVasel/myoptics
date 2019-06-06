<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>

	<a href="{{Request::root()}}" style="margin-bottom:20px;"><img src="{{Request::root()}}/uploads/logo/logo.png"></a>

	<h2 style="margin-bottom:20px;">Уведомление о новом заказе</h2>

	<div style="font-weight: bold; font-size: 14px; line-height: 24px; margin-bottom: 20px;">
		<span style="color: #cccccc; display: inline-block; width: 130px;">Заказ оформил: </span>{{$post->name}} <br>
		<span style="color: #cccccc; display: inline-block; width: 130px;">Телефон: </span>{{$post->phone}} <br>
	</div>

	<table style="font-size: 14px; border-collapse: collapse; border-spacing: 0;">
		<thead>
			<tr>
				<td colspan="5" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;"><span style="font-weight: bold; margin-right: 40px;">Заказ № {{ $post->id }}</span> {{ $post->created_at->format('d.m.Y G:i:s') }}</td>
			</tr>



			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">Имя</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->name }}</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">Фамилия</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->surname }}</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">Телефон</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->phone }}</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">eMail</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->email }}</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">Адрес доставки</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->address }}</td>
			</tr>





			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">Вариант доставки</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $delivery->name }}</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc;">Способ оплаты</td>
				<td colspan="2" style="padding: 5px 0; font-size: 16px; text-align: left; vertical-align: middle;">{{ $payment->name }}</td>
			</tr>
		</thead>
		<tbody>
			<tr class="table-head" style="background: #dd8335; color: #ffffff; text-transform: uppercase; line-height: 44px; text-align: center;">
				<th width="15%">Изображение</th>
				<th width="25%">Название</th>
				<th width="20%">Количество</th>
				<th width="20%">Цена (за 1 шт.)</th>
				<th width="20%">Сумма</th>
			</tr>
			@foreach( $ordered_prod as $op )
				<tr>
					<td style="padding: 5px 0; font-size: 14px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc; ">
						<img style="height: 75px;" src="{{--*/ $img = unserialize($op->prod_imgs)[0] /*--}} {{Request::root()}}/uploads/products/thumbs/{{ $img }}" alt="{{ $op->prod_name }}">
					</td>
					<td style="padding: 5px 0; font-size: 14px; text-align: left; vertical-align: middle; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;">
						<a href="{{Request::root()}}/product/{{ $op->prod_slug }}">{{ $op->prod_name }}</a>
					</td>
					<td style="padding: 5px 0; font-size: 14px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;">
						{{ $op->quantity }} шт.
					</td>
					<td style="padding: 5px 0; font-size: 14px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;">
						{{ $op->cost }} грн.
					</td>
					<td style="padding: 5px 0; font-size: 24px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;">
						{{ $op->cost * $op->quantity }} грн.
					</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc;">Доставка ({{ $delivery->name }})</td>
				<td colspan="2" style="padding: 5px 0; font-size: 20px; font-weight: bold; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $delivery->price }} грн.</td>
			</tr> 
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc;">Общая стоимость продукции</td>
				<td colspan="2" style="padding: 5px 0; font-size: 20px; font-weight: bold; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->total_cost  }} грн.</td>
			</tr>			
			<tr>
				<td colspan="3" style="padding: 5px 0; font-size: 16px; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc;">Общая стоимость заказа</td>
				<td colspan="2" style="padding: 5px 0; font-size: 20px; font-weight: bold; text-align: center; vertical-align: middle; border-bottom: 1px solid #cccccc;">{{ $post->total_cost }} грн.</td>
			</tr>
		</tfoot>
	</table>


	<p>Данное письмо создано автоматически, пожалуйста, не отвечайте на него.</p>

</body>
</html>