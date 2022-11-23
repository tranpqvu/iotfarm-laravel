@extends('layouts.account')

@section('content')
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <h2 class="sr-only">{{ __('Reset Password') }}</h2>
            <div class="illustration"><img src="{{asset('assets_login/img/logo.png')}}" class="logo-image"></div>    
            <div class="form-group">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email reset password">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
</body>
</html>
@endsection
