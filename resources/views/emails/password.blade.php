<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Сообщение от {{ $site }}</h2>
<br/> <br/>
<p>Был получен запрос на восстановление пароля на сайте {{ $site }}. </p>
<p><strong>Ваш новый пароль:</strong>{{ $newPassword }}</p>
<p>Если запроса с Вашей стороны небыло просто проигнорируейте данное письмо.</p>
<p>На данное письмо не стоит отвечать. </p>
</body>
</html>
