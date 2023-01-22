<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>password recovery</title>
</head>
<body>
    <h2>Recuperar contraseña</h2>

    <p>Token: {{ $token }}</p>

    {{-- <form action="{{ route('editPassword') }}" method="post">
        @csrf
        @method('PATCH')
        <label for="">Nueva contraseña</label><br>
        <input type="hidden" value="{{ $user->id }}" name="id">
        <input type="password" name="password">
        <input type="submit" value="Enviar">
    </form> --}}
</body>
</html>