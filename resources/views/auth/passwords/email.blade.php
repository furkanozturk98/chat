<!DOCTYPE html>
<html>
<head>
    <title></title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

</head>
<body>

<div class="login-form" style="width: 400px">
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <h2 class="text-center"> {{__('Reset Password')}}</h2>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

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
            <button type="submit" class="btn btn-primary btn-lg btn-block">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </form>
</div>
</body>
</html>
