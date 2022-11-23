@extends('layouts.account')

@section('content')
    <form method="post" action="{{ route('login') }}">
		@csrf
	    <h2 class="sr-only">Login Form</h2>
	    <div class="illustration"><img src="{{asset('assets/assets_login/img/logo.png')}}" class="logo-image"></div>
	    <div class="form-group">
			<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>

	        @error('email')
	            <span class="invalid-feedback" role="alert">
	                <strong>{{ $message }}</strong>
	            </span>
	        @enderror
	    </div>
	    <div class="form-group">
	    	<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Mật khẩu">

	    	@error('password')
	            <span class="invalid-feedback" role="alert">
	                <strong>{{ $message }}</strong>
	            </span>
	    	@enderror
	    </div>
	    <div class="form-group">
	    	<button class="btn btn-info btn-block" type="submit">Đăng Nhập</button></div>
	        @if (Route::has('password.request'))
	            <a class="forgot" href="{{ route('password.request') }}">
	                <strong>Quên Mật Khẩu?</strong>
	            </a><br/>
	            <!--a class="forgot" href="{{ route('register') }}">
	                <strong>Đăng Ký</strong>
	            </a-->
	        @endif
	</form>
@endsection