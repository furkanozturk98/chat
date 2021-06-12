<!DOCTYPE html>
<html>
<head>
    <title></title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

</head>
<body>

<div class="login-form" style="width: 400px">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <h2 class="text-center">{{ __('Reset Password') }}</h2>

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group has-error">
            <input class="form-control @error('email') is-invalid @enderror"
                   name="email" placeholder="Email"
                   value="{{ old('email') }}"
                   >

            @error('email')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror

        </div>

        <div class="form-group">
                <input id="password"
                       type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" placeholder="Password"
                       autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
        </div>

        <div class="form-group">
                <input id="password-confirm"
                       type="password"
                       class="form-control"
                       name="password_confirmation" placeholder="Password Confirmation"
                       autocomplete="new-password">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
</body>
</html>

