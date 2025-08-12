<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Sign-up Flow</title>
    <link href="/css/style.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    {{-- Sign-up form --}}
    @if(!isset($codeKey) || $codeKey === null)
    <form action="/confirm" method="post">
        @csrf
        <div class="full-form">
            <h2>SIGN UP</h2><hr>
            <div class="form-div">
                <label for="username">EMAIL</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}">
            </div>
            <div class="form-div">
                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-div">    
                <label for="password-confirmation">CONFIRM PASSWORD</label>
                <input type="password" name="password_confirmation" id="password-confirmation">
            </div>
            <input type="submit" class="submit-bt" value="SIGN UP" >
            <br><br>
            <p class="password-info">
                Please create a password that is at least 6 characters long and contains a combination of letters and numbers.
            </p>
        </div>
    </form>
    @endif

    {{-- Confirmation code form --}}
    @if(isset($codeKey) && $codeKey === 'confirm')
    <form action="/confirm-code" method="post">
        @csrf
        <div class="code-form">
            <div class="code-form-div">
                <label for="number_1">CODE</label>
            </div>
            <div class="code-form-div">
                <input type="number" name="number_1" id="number_1" min="0" max="9" required>
                <input type="number" name="number_2" id="number_2" min="0" max="9" required>
                <input type="number" name="number_3" id="number_3" min="0" max="9" required>
                <input type="number" name="number_4" id="number_4" min="0" max="9" required>
            </div>
            <input type="hidden" name="username" value="{{ $email }}" />
            <input type="submit" name="confirm-code" class="submit-bt code-bt" value="CONFIRM" >
            <p>Your sign-up code has been sent to the email address below:</p>
            <p>{{ $email }}</p>
        </div>
    </form>
    @endif

    {{-- Final success message --}}
    @if(isset($codeKey) && $codeKey === 'final')
        <div class="code-form">
            <form action="/" method="get">
                <p>Your registration has been completed successfully!</p>
                <p>Welcome aboard!</p>
                <p>{{ $email }}</p>
                <input type="submit" value="OK"/>
            </form>
        </div>
    @endif

    {{-- Error messages --}}
    @if(isset($errorKey))
        @if($errorKey === 'form_1')
            <form action="/" method="get">
                <div class="error-form">
                    @foreach ($errorMessage as $message)
                        <p><b>Error:</b> {{ $message }}</p>
                    @endforeach
                    <input type="submit" class="submit-bt" value="TRY IT AGAIN" >
                </div>
            </form>
        @elseif($errorKey === 'form_2')
            <form action="/confirm-code-error" method="post">
                @csrf
                <input type="hidden" name="username" value="{{ $email }}">
                <div class="error-form">
                    @foreach ($errorMessage as $message)
                        <p><b>Error:</b> {{ $message }}</p>
                    @endforeach
                    <input type="submit" class="submit-bt" value="TRY IT AGAIN" >
                </div>
            </form>
        @endif
    @endif

</body>
</html>
