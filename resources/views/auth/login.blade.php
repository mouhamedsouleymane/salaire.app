<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
</head>

<body>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <div class="login-container">
        <h1><strong>Connectez-vous</strong></h1>


        <strong>
            @if (Session::get('error_msg'))
                <b style="font-size: 10px; color:red">{{ Session::get('error_msg') }}</b>
            @endif
        </strong>

        <form method="post" action="{{ route('handleLogin') }}">
            @csrf
            @method('POST')


            <div class="form-group">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <p>Vous n'avez pas de compte ? <a href="#">Contactez l'administration</a></p>
    </div>
</body>

</html>
