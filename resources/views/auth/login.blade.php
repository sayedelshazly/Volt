@extends('layouts.app')
@section('title', __('Login'))
@section('content')

<div class="auth card" data-aos="fade-up">
  <div style="text-align:center;margin-bottom:24px">
    <a href="/" class="logo" style="display:inline-flex;margin-bottom:8px">
      <span class="logo-bolt">⚡</span> VOLT
    </a>
    <h2>{{ __('Login') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Smart tech & fine fragrances') }}</p>
  </div>

  <form method="POST" action="/login" novalidate>
    @csrf

    <div>
      <label for="email">{{ __('Email') }}</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus autocomplete="email">
      @error('email')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div style="margin-top:14px">
      <label for="password">{{ __('Password') }}</label>
      <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      @error('password')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin:16px 0">
      <label style="display:inline-flex;align-items:center;gap:8px;margin:0;cursor:pointer;font-weight:500;font-size:13.5px">
        <input type="checkbox" name="remember" style="width:auto;cursor:pointer">
        {{ __('Remember me') }}
      </label>
    </div>

    <button type="submit" class="btn lg" style="width:100%">
      {{ __('Login') }} →
    </button>

    <div style="text-align:center;margin-top:20px;font-size:14px;color:var(--mute)">
      {{ __('No account?') }}
      <a href="/register" style="color:var(--blue);font-weight:700">
        {{ __('Register') }}
      </a>
    </div>
  </form>
</div>

@endsection
