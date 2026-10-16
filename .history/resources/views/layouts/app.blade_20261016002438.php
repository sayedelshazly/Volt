@php $ar = app()->getLocale()==='ar'; $cc = array_sum(session('cart', [])); $cur = config('volt.currency'); @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $ar ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'VOLT') | VOLT</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<link rel="stylesheet" href="{{ asset('css/volt.css') }}">
<script src="{{ asset('js/volt.js') }}"></script>
<script defer src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
</head>
<body>
<header class="top"><div class="wrap nav">
  <a href="/" class="logo">⚡ VOLT</a>
  <nav id="menu">
    <a href="/" class="{{ request()->is('/') ? 'on' : '' }}">{{ __('Home') }}</a>
    <a href="/shop" class="{{ request()->is('shop') ? 'on' : '' }}">{{ __('Electronics') }}</a>
    <a href="/perfumes" class="{{ request()->is('perfumes') ? 'on' : '' }}">{{ __('Perfumes') }}</a>
    <a href="/track">{{ __('Track order') }}</a>
  </nav>
  <form class="search" action="/shop"><span>🔍</span><input name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}" aria-label="{{ __('Search') }}"></form>
  <div class="tools">
    <a class="ic" href="/lang/{{ $ar ? 'en' : 'ar' }}" title="Language">{{ $ar ? 'EN' : 'ع' }}</a>
    <button class="ic" id="theme" aria-label="Theme"></button>
    <a class="ic" href="/cart" aria-label="{{ __('Cart') }}">🛒<span class="badge" id="cartCount" @if(!$cc) hidden @endif>{{ $cc }}</span></a>
    @auth
      @if(in_array(auth()->user()->role, ['admin','editor']))<a class="ic" href="/admin" title="Dashboard">📊</a>@endif
      <a class="ic" href="/account/orders" title="{{ __('My orders') }}">👤</a>
      <form method="POST" action="/logout">@csrf<button class="ic" title="{{ __('Logout') }}">⎋</button></form>
    @else
      <a class="btn sm" href="/login">{{ __('Login') }}</a>
    @endauth
    <button class="ic burger" id="burger" aria-label="Menu">☰</button>
  </div>
</div></header>
<main class="wrap">
  @if(session('ok'))<div class="flash ok">{{ session('ok') }}</div>@endif
  @if(session('err'))<div class="flash err">{{ session('err') }}</div>@endif
  @yield('content')
</main>
<footer><div class="wrap">© {{ date('Y') }} VOLT — {{ __('Smart tech & fine fragrances') }}</div></footer>
<a class="wa" target="_blank" rel="noopener" href="https://wa.me/{{ config('volt.whatsapp') }}?text={{ rawurlencode(__('Hello VOLT, I have a question')) }}" aria-label="WhatsApp">💬</a>
</body></html>
