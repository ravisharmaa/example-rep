@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center loginWrapper forgotWrapper">
        <div class="col-md-12">
            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                   <span>
                                 <svg id="icon-check" viewBox="0 0 225 225" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5;">
                                                         <g transform="matrix(1,0,0,1,-178.667,-170.667)">
                                                                 <g class="check" transform="matrix(1,0,0,1,176,113)">
                                                                         <path d="M65,166L101,202L165,138" style="fill:none;stroke:#ffffff;stroke-width:16.67px;" />
                                                                 </g>
                                                                 <circle class="circle" cx="291" cy="283" r="104" style="fill:none;stroke:#fff;stroke-width:16.67px;" />
                                                         </g>
                                                 </svg>
                                   </span>
                                   {{ session('status') }}
                                </div>
                            @endif
            <div class="card loginDiv">


                <div class="card-body">

                    <div class="loginHero forgotHero">

<img src="{{asset('img/forgot.png')}}">
</div>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="card-header">{{ __('Reset Password') }}</div>
                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label ">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 ">
                                <button type="submit" class="btn btn-primary btnLogin btnForgot">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
