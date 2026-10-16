@extends('layouts.app')
@section('title', __('Home'))
@section('content')
<section class="hero" data-aos="fade-up">
  <div class="hero-content">
    <div class="hero-pill">⚡ {{ __('Power up your everyday') }}</div>
    <h1>{{ __('Power up your everyday') }}</h1>
    <p>{{ __('Smart watches, earbuds, mobile accessories and a dedicated perfume collection — all in one store.') }}</p>
    <div class="btns">
      <a class="btn" href="/shop">⚡ {{ __('Shop electronics') }}</a>
      <a class="btn ghost" href="/perfumes">🧴 {{ __('Explore perfumes') }}</a>
    </div>
  </div>
  <div class="orbs" aria-hidden="true">
    <div class="orb o1">⌚</div>
    <div class="orb o2">🎧</div>
    <div class="orb o3">🧴</div>
  </div>
</section>

<!-- Trust Perks Bar -->
<div class="trust-bar" data-aos="fade-up" data-aos-delay="100">
  <div class="trust-item">
    <span class="trust-icon">🚚</span>
    <div>
      <div class="trust-title">{{ __('Fast shipping') }}</div>
      <div class="trust-desc">{{ __('Free delivery over :amount', ['amount' => number_format(config('volt.free_shipping_over')).' '.config('volt.currency')]) }}</div>
    </div>
  </div>
  <div class="trust-item">
    <span class="trust-icon">🛡️</span>
    <div>
      <div class="trust-title">{{ __('100% Genuine') }}</div>
      <div class="trust-desc">{{ __('1-Year Warranty') }}</div>
    </div>
  </div>
  <div class="trust-item">
    <span class="trust-icon">🔄</span>
    <div>
      <div class="trust-title">{{ __('14-Day Returns') }}</div>
      <div class="trust-desc">{{ __('Hassle-free guarantee') }}</div>
    </div>
  </div>
  <div class="trust-item">
    <span class="trust-icon">💬</span>
    <div>
      <div class="trust-title">{{ __('Need help?') }}</div>
      <div class="trust-desc">{{ __('Chat on WhatsApp') }}</div>
    </div>
  </div>
</div>

<!-- Categories Section -->
<div class="sec">
  <h2>🗂️ {{ __('Categories') }}</h2>
  <a href="/shop" class="btn ghost sm">{{ __('View all') }} →</a>
</div>
<div class="cats">
  @foreach($cats as $c)
    <a class="cat" data-aos="zoom-in" href="/shop?category={{ $c->id }}">
      <i>{{ $c->icon }}</i>
      <span>{{ $c->name }}</span>
    </a>
  @endforeach
  <a class="cat" data-aos="zoom-in" href="/perfumes">
    <i>🧴</i>
    <span>{{ __('Perfumes') }}</span>
  </a>
</div>

<!-- Featured Products Section -->
<div class="sec">
  <div>
    <h2>🔥 {{ __('Featured products') }}</h2>
  </div>
  <a href="/shop" class="btn ghost sm">{{ __('View all') }} →</a>
</div>
<div class="grid">
  @foreach($featured as $p)
    @include('partials.card')
  @endforeach
</div>

<!-- Dedicated Perfume Spotlight Section -->
@if($perfumes->count())
<section class="perf" data-aos="fade-up">
  <div class="sec" style="margin-top:0">
    <div>
      <h2 style="color:#fff">🧴 {{ __('Perfume collection') }}</h2>
      <p style="color:rgba(255,255,255,0.8);font-size:14px;margin-top:4px">{{ __('Smart tech & fine fragrances') }}</p>
    </div>
    <a href="/perfumes" class="btn sm" style="background:#fff;color:#351a7a">
      {{ __('View all') }} →
    </a>
  </div>
  <div class="grid">
    @foreach($perfumes as $p)
      @include('partials.card')
    @endforeach
  </div>
</section>
@endif

@endsection