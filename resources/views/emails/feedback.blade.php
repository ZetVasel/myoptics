<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Сообщение от {{ $unserialized['name'] }}</h2>
	<br/> <br/>
	<p><strong>Имя:</strong> {{ $unserialized['name'] }}</p>
	<p><strong>Email:</strong> {{ $unserialized['email'] }}</p> 
	<p><strong>Сообщение:</strong> {{ $unserialized['comment'] }}</p> 
</body>
</html>