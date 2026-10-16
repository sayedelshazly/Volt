@php
  $ar = app()->getLocale() === 'ar';
  $user = auth()->user();
  $role = $user->role ?? 'editor';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $ar ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | VOLT Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/volt.css') }}">
  <script src="{{ asset('js/volt.js') }}"></script>
</head>

<body>
  <div class="adm">
    <!-- Admin Sidebar -->
    <aside class="side">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
        <a href="/" class="logo" style="margin-bottom:0">
          <span class="logo-bolt">⚡</span> VOLT
        </a>
        <span class="pill {{ $role==='admin' ? 'paid' : 'shipped' }}" style="font-size:11px;text-transform:uppercase">
          {{ $role }}
        </span>
      </div>

      <a href="/admin" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">
        <span>📊</span> <span>{{ __('Dashboard') }}</span>
      </a>

      <a href="/admin/products" class="{{ request()->routeIs('admin.products.*') ? 'on' : '' }}">
        <span>📦</span> <span>{{ __('Products') }}</span>
      </a>

      <a href="/admin/orders" class="{{ request()->routeIs('admin.orders.*') ? 'on' : '' }}">
        <span>🧾</span> <span>{{ __('Orders') }}</span>
      </a>

      @if($role === 'admin')
        <a href="/admin/categories" class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}">
          <span>🗂️</span> <span>{{ __('Categories') }}</span>
        </a>
      @endif

      <div class="side-footer">
        <a href="/lang/{{ $ar ? 'en' : 'ar' }}">
          <span>🌐</span> <span>{{ $ar ? 'English' : 'العربية' }}</span>
        </a>

        <a href="#" class="theme-toggle-btn" onclick="return false">
          <span>🌙</span> <span>{{ __('Theme') }}</span>
        </a>

        <a href="/" target="_blank">
          <span>🏪</span> <span>{{ __('View store') }} ↗</span>
        </a>

        <form method="POST" action="/logout" style="margin-top:12px">
          @csrf
          <button class="btn ghost sm" style="width:100%;color:var(--err)!important;border-color:var(--err-border)">
            ⎋ {{ __('Logout') }}
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main">
      @if(session('ok'))
        <div class="flash ok">
          <span>✓</span> {{ session('ok') }}
        </div>
      @endif

      @if(isset($errors) && $errors->any())
        <div class="flash err">
          <span>⚠️</span> {{ $errors->first() }}
        </div>
      @endif

      @yield('content')
    </main>
  </div>
</body>

</html>