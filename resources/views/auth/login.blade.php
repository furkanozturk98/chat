<!DOCTYPE html>
<html>
<head>
    <title></title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

</head>
<body>

<div class="login-form">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <h2 class="text-center">Login</h2>

        <div class="social">
            <h4>Connect with</h4>
            <ul>
                <li>
                    <a href="/login/facebook" class="facebook">
                        <span class="fa fa-facebook"></span>
                    </a>
                </li>
                <li>
                    <a href="" class="twitter">
                        <span class="fa fa-twitter"></span>
                    </a>
                </li>
                <li>
                    <a href="/login/google" class="google-plus">
                        <span class="fa fa-google-plus"></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="divider">
            <span>or</span>
        </div>

        <div class="form-group">

            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
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
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Password"
                   >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
        </div>
        <p><a href="{{route('password.request')}}">Lost your Password?</a></p>
    </form>
    <p class="text-center small">Don't have an account? <a href="{{route('register')}}">Sign up here!</a></p>
</div>
</body>
</html>

