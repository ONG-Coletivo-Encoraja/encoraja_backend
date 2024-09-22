<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coletivo Encoraja</title>
</head>

<body>
    <div style="width: 100%; display: flex; justify-content: center;">
        <img src="{{ asset('img/coletivo_encoraja.jpg') }}" alt="Descrição da imagem" width="150">
    </div>

    <p>Prezada(o) {{$user->name}}</p>

    <p>Para recuperar a sua senha do site Coletivo Encoraja, use o código de verificação abaixo:</p>

    <p>{{ $code }}</p>

    <p>Por questões de segurança esse código é válido somente até as {{ $formattedTime }} do dia {{ $formattedDate }}. Caso esse prazo esteja expirado, será necessário solicitar outro código.</p>

    <p>Atenciosamente,</p>

    <p>Coletivo Encoraja</p>

</body>

</html>
