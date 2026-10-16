@extends('layouts.app')
@section('title', __('Login'))
@section('content')
<form class="card auth" method="POST" action="/login">@csrf<h2>{{ __('Login') }}</h2>
<label>{{ __('Email') }}</label><input type="email" name="email" value="{{ old('email') }}" required autofocus>@error('email')<div class="err">{{ $message }}</div>@enderror
<label>{{ __('Password') }}</label><input type="password" name="password" required>
<label style="font-weight:500"><input type="checkbox" name="remember" style="width:auto"> {{ __('Remember me') }}</label>
<button class="btn" style="width:100%;margin-top:14px">{{ __('Login') }}</button>
<p style="margin-top:14px">{{ __('No account?') }} <a href="/register" style="color:var(--blue)">{{ __('Register') }}</a></p></form>
@endsection
