@extends('layouts.app')
@section('title', __('Register'))
@section('content')

<div class="auth card" data-aos="fade-up">
  <div style="text-align:center;margin-bottom:24px">
    <a href="/" class="logo" style="display:inline-flex;margin-bottom:8px">
      <span class="logo-bolt">⚡</span> VOLT
    </a>
    <h2>{{ __('Register') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Create your account to start shopping') }}</p>
  </div>

  <form method="POST" action="/register" novalidate>
    @csrf

    <div>
      <label for="reg_name">{{ __('Full name') }}</label>
      <input id="reg_name" type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Full name') }}" required autofocus autocomplete="name">
      @error('name')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div style="margin-top:12px">
      <label for="reg_email">{{ __('Email') }}</label>
      <input id="reg_email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autocomplete="email">
      @error('email')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div style="margin-top:12px">
      <label for="reg_phone">{{ __('Phone') }}</label>
      <input id="reg_phone" type="tel" name="phone" dir="ltr" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required autocomplete="tel">
      @error('phone')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div style="margin-top:12px">
      <label for="reg_password">{{ __('Password') }}</label>
      <input id="reg_password" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
      @error('password')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div style="margin-top:12px">
      <label for="reg_password_confirmation">{{ __('Confirm password') }}</label>
      <input id="reg_password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
    </div>

    <button type="submit" class="btn lg" style="width:100%;margin-top:22px">
      {{ __('Register') }} →
    </button>

    <div style="text-align:center;margin-top:20px;font-size:14px;color:var(--mute)">
      {{ __('Already have an account?') }}
      <a href="/login" style="color:var(--blue);font-weight:700">
        {{ __('Login') }}
      </a>
    </div>
  </form>
</div>

@endsection
