<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Signup Flow</title>
        <link href="/css/style.css" rel="stylesheet" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <form action="/confirm" method='post'>
            @csrf
            @if(isset($teste))
                {{ $teste }}
            @endif
            <div class="full-form">
                <h2>SIGN UP</h2><hr>
                <p>
                    <label for="username">EMAIL</label>
                    <input type="email" name="username" id="username">
                </p>
                <p>
                    <label for="password">PASSWORD</label>
                    <input type="password" name="password" id="password">
                </p>
                <p>    
                    <label for="confirm-password">CONFIRM PASSWORD</label>
                    <input type="password" id="confirm-password">
                </p>
                    <input type="submit" name="confirm-password" class="submit-bt" value="SIGN UP" >
                    <br><br>
            </div>
        </form>
    </body>
</html>
