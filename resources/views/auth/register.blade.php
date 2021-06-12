<!DOCTYPE html>
<html>
<head>
    <title></title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

</head>
<body>

<div class="login-form">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h2 class="text-center">Register</h2>


        <div class="form-group has-error">

            <input id="name"
                   type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   name="name"
                   placeholder="Name"
                   value="{{ old('name') }}" autocomplete="name" autofocus>

            @error('name')
                <span class="invalid-feedback" role="alert">
                       <strong>{{ $message }}</strong>
                 </span>
            @enderror
        </div>

        <div class="form-group has-error">

            <input id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email"
                   placeholder="Email"
                   value="{{ old('email') }}" autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group has-error">

                <input id="password"
                       type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       placeholder="Password"
                       autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
        </div>

        <div class="form-group has-error">

                <input id="password-confirm"
                       type="password"
                       class="form-control"
                       placeholder="Confirm Password"
                       name="password_confirmation" autocomplete="new-password">
        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
        </div>
    </form>
</div>
</body>
</html>

