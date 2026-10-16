@php $ar = app()->getLocale()==='ar'; $role=auth()->user()->role; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $ar ? 'rtl' : 'ltr' }}"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title') | VOLT Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/volt.css') }}"><script src="{{ asset('js/volt.js') }}"></script>
</head><body><div class="adm">
<aside class="side"><a href="/" class="logo" style="display:block;margin-bottom:14px">⚡ VOLT</a>
  <a href="/admin" class="{{ request()->routeIs('admin.dashboard')?'on':'' }}">📊 {{ __('Dashboard') }}</a>
  <a href="/admin/products" class="{{ request()->routeIs('admin.products.*')?'on':'' }}">📦 {{ __('Products') }}</a>
  <a href="/admin/orders" class="{{ request()->routeIs('admin.orders.*')?'on':'' }}">🧾 {{ __('Orders') }}</a>
  @if($role==='admin')<a href="/admin/categories" class="{{ request()->routeIs('admin.categories.*')?'on':'' }}">🗂️ {{ __('Categories') }}</a>@endif
  <hr style="border-color:var(--line);margin:12px 0">
  <a href="/lang/{{ $ar?'en':'ar' }}">🌐 {{ $ar?'English':'العربية' }}</a><a href="#" id="theme" onclick="return false">🌙</a><a href="/">🏪 {{ __('View store') }}</a>
  <form method="POST" action="/logout">@csrf<button class="btn ghost sm" style="width:100%;margin-top:8px">{{ __('Logout') }}</button></form>
</aside>
<div class="main">
  @if(session('ok'))<div class="flash ok">{{ session('ok') }}</div>@endif
  @if($errors->any())<div class="flash err">{{ $errors->first() }}</div>@endif
  @yield('content')
</div></div></body></html>
