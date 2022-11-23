@extends('layouts.account')

@section('content')
    <form method="post" action="#">
        @csrf
        <h2 class="sr-only">{{ __('Verify Your Email Address') }}</h2>
        <div class="illustration"><img src="{{asset('assets_login/img/logo.png')}}" class="logo-image"></div>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
    </form>
</div>
@endsection
