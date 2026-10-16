@php $ar = app()->getLocale()==='ar'; $cc = array_sum(session('cart', [])); $cur = config('volt.currency'); @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $ar ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'VOLT') | VOLT</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
  <link rel="stylesheet" href="{{ asset('css/volt.css') }}">
  <script src="{{ asset('js/volt.js') }}"></script>
  <script defer src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
</head>

<body>
  <!-- Header Bar -->
  <header class="top">
    <div class="wrap nav">
      <a href="/" class="logo" aria-label="VOLT Home">
        <span class="logo-bolt">⚡</span> VOLT
      </a>

      <!-- Desktop Navigation (Visible only on screens > 860px) -->
      <nav class="nav-desktop">
        <a href="/" class="{{ request()->is('/') ? 'on' : '' }}">{{ __('Home') }}</a>
        <a href="/shop" class="{{ request()->is('shop') ? 'on' : '' }}">{{ __('Electronics') }}</a>
        <a href="/perfumes" class="{{ request()->is('perfumes') ? 'on' : '' }}">{{ __('Perfumes') }}</a>
        <a href="/track" class="{{ request()->is('track') ? 'on' : '' }}">{{ __('Track order') }}</a>
      </nav>

      <!-- Desktop Search Bar -->
      <form class="search" action="/shop" role="search">
        <span>🔍</span>
        <input name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}" aria-label="{{ __('Search') }}">
      </form>

      <!-- Action Tools -->
      <div class="tools">
        <a class="ic" href="/lang/{{ $ar ? 'en' : 'ar' }}" title="{{ $ar ? 'Switch to English' : 'التحويل للعربية' }}" aria-label="Change Language">
          {{ $ar ? 'EN' : 'ع' }}
        </a>
        <button type="button" class="ic" id="theme" aria-label="Toggle theme" title="Theme"></button>
        <a class="ic" href="/cart" aria-label="{{ __('Cart') }}" title="{{ __('Cart') }}">
          🛒<span class="badge" id="cartCount" @if(!$cc) hidden @endif>{{ $cc }}</span>
        </a>

        @auth
          @if(in_array(auth()->user()->role, ['admin','editor']))
            <a class="ic" href="/admin" title="{{ __('Dashboard') }}" aria-label="{{ __('Dashboard') }}">📊</a>
          @endif
          <a class="ic" href="/account/orders" title="{{ __('My orders') }}" aria-label="{{ __('My orders') }}">👤</a>
          <form method="POST" action="/logout" style="display:inline">
            @csrf
            <button class="ic" title="{{ __('Logout') }}" aria-label="{{ __('Logout') }}">⎋</button>
          </form>
        @else
          <a class="btn sm" href="/login">{{ __('Login') }}</a>
        @endauth

        <!-- Burger Toggle for Mobile -->
        <button type="button" class="ic burger" id="burger" aria-label="{{ __('Menu') }}" title="Menu">☰</button>
      </div>
    </div>
  </header>

  <!-- Mobile Drawer Backdrop Overlay (z-index: 998) -->
  <div id="menuBackdrop" class="menu-backdrop"></div>

  <!-- Standalone Mobile Navigation Drawer (z-index: 999, directly under body, completely crisp & clickable) -->
  <aside id="mobileDrawer" class="mobile-drawer" aria-label="Mobile Navigation" aria-hidden="true">
    <div class="drawer-header">
      <a href="/" class="logo">
        <span class="logo-bolt">⚡</span> VOLT
      </a>
      <button type="button" class="drawer-close" id="drawerClose" aria-label="{{ __('Close') }}">✕</button>
    </div>

    <!-- Mobile Drawer Search -->
    <form class="drawer-search" action="/shop" role="search">
      <span class="drawer-search-icon">🔍</span>
      <input name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}" aria-label="{{ __('Search') }}">
    </form>

    <!-- Navigation Links -->
    <nav class="drawer-nav">
      <a href="/" class="{{ request()->is('/') ? 'on' : '' }}">
        <span class="d-icon">🏠</span> <span>{{ __('Home') }}</span>
      </a>
      <a href="/shop" class="{{ request()->is('shop') ? 'on' : '' }}">
        <span class="d-icon">⚡</span> <span>{{ __('Electronics') }}</span>
      </a>
      <a href="/perfumes" class="{{ request()->is('perfumes') ? 'on' : '' }}">
        <span class="d-icon">🧴</span> <span>{{ __('Perfumes') }}</span>
      </a>
      <a href="/track" class="{{ request()->is('track') ? 'on' : '' }}">
        <span class="d-icon">📦</span> <span>{{ __('Track order') }}</span>
      </a>
    </nav>

    <!-- Drawer Footer Actions -->
    <div class="drawer-footer">
      <div style="display:flex;gap:10px;margin-bottom:12px">
        <a class="btn ghost sm" style="flex:1" href="/lang/{{ $ar ? 'en' : 'ar' }}">
          🌐 {{ $ar ? 'English (EN)' : 'العربية (AR)' }}
        </a>
        <button type="button" class="btn ghost sm theme-toggle-btn" style="width:44px" aria-label="Toggle Theme">
          🌙
        </button>
      </div>

      @auth
        <a class="btn ghost sm" style="width:100%;margin-bottom:8px" href="/account/orders">
          👤 {{ __('My orders') }}
        </a>
        @if(in_array(auth()->user()->role, ['admin','editor']))
          <a class="btn ghost sm" style="width:100%;margin-bottom:8px" href="/admin">
            📊 {{ __('Dashboard') }}
          </a>
        @endif
        <form method="POST" action="/logout">
          @csrf
          <button type="submit" class="btn danger sm" style="width:100%">
            ⎋ {{ __('Logout') }}
          </button>
        </form>
      @else
        <div style="display:flex;gap:8px">
          <a class="btn sm" style="flex:1" href="/login">{{ __('Login') }}</a>
          <a class="btn ghost sm" style="flex:1" href="/register">{{ __('Register') }}</a>
        </div>
      @endauth
    </div>
  </aside>

  <!-- Main Content Wrap -->
  <main class="wrap">
    @if(session('ok'))
      <div class="flash ok" data-aos="fade-down">
        <span>✓</span> {{ session('ok') }}
      </div>
    @endif
    @if(session('err'))
      <div class="flash err" data-aos="fade-down">
        <span>⚠️</span> {{ session('err') }}
      </div>
    @endif

    @yield('content')
  </main>

  <!-- Professional Footer -->
  <footer>
    <div class="wrap">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="/" class="logo"><span class="logo-bolt">⚡</span> VOLT</a>
          <p>{{ __('Smart tech & fine fragrances') }} — {{ __('Smart watches, earbuds, mobile accessories and a dedicated perfume collection — all in one store.') }}</p>
          <div style="display:flex;gap:10px;margin-top:12px">
            <span class="pay-badge">⚡ {{ __('Fast shipping') }}</span>
            <span class="pay-badge">🛡️ {{ __('100% Genuine') }}</span>
          </div>
        </div>
        <div class="footer-col">
          <h4>{{ __('Quick Links') }}</h4>
          <ul>
            <li><a href="/">{{ __('Home') }}</a></li>
            <li><a href="/shop">{{ __('Electronics') }}</a></li>
            <li><a href="/perfumes">{{ __('Perfumes') }}</a></li>
            <li><a href="/track">{{ __('Track order') }}</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>{{ __('Customer') }}</h4>
          <ul>
            <li><a href="/cart">{{ __('Cart') }}</a></li>
            <li><a href="/account/orders">{{ __('My orders') }}</a></li>
            @guest
              <li><a href="/login">{{ __('Login') }}</a></li>
              <li><a href="/register">{{ __('Register') }}</a></li>
            @endguest
          </ul>
        </div>
        <div class="footer-col">
          <h4>{{ __('Contact Us') }}</h4>
          <ul>
            <li><a target="_blank" rel="noopener" href="https://wa.me/{{ config('volt.whatsapp') }}">💬 {{ __('Chat on WhatsApp') }}</a></li>
            <li><span style="font-size:13px;color:var(--mute)">📦 {{ __('Free delivery over :amount', ['amount' => number_format(config('volt.free_shipping_over')).' '.$cur]) }}</span></li>
            <li><span style="font-size:13px;color:var(--mute)">🔒 {{ __('Secure Checkout') }}</span></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div>© {{ date('Y') }} VOLT. {{ __('All rights reserved') }}.</div>
        <div class="payment-badges">
          <span class="pay-badge">💵 {{ __('Cash on delivery') }}</span>
          <span class="pay-badge">💳 Visa / Mastercard</span>
          <span class="pay-badge">🔒 256-bit SSL</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to top button -->
  <button type="button" id="backToTop" class="back-to-top" aria-label="{{ __('Back to top') }}" title="{{ __('Back to top') }}">↑</button>

  <!-- WhatsApp Floating Button -->
  <a class="wa" target="_blank" rel="noopener"
    href="https://wa.me/{{ config('volt.whatsapp') }}?text={{ rawurlencode(__('Hello VOLT, I have a question')) }}"
    aria-label="{{ __('Chat on WhatsApp') }}" title="{{ __('Chat on WhatsApp') }}">💬</a>
</body>

</html>