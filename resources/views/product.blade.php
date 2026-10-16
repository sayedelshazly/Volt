@extends('layouts.app')
@section('title', $p->name)
@section('content')

@php
  $cur = config('volt.currency');
  $savedAmount = $p->old_price ? ($p->old_price - $p->price) : 0;
  $waMsg = rawurlencode(__('I want to order:').' '.$p->name.' — '.url('/product/'.$p->id));
@endphp

<!-- Breadcrumbs -->
<div style="display:flex;align-items:center;gap:8px;margin:20px 0 16px;font-size:13.5px;color:var(--mute);flex-wrap:wrap">
  <a href="/">{{ __('Home') }}</a>
  <span>›</span>
  <a href="{{ $p->category->section==='perfume' ? '/perfumes' : '/shop' }}">
    {{ $p->category->name ?? __('Electronics') }}
  </a>
  <span>›</span>
  <span style="color:var(--ink);font-weight:600">{{ $p->name }}</span>
</div>

<div class="product-details-grid">
  <!-- Product Media Gallery -->
  <div class="product-gallery card">
    @if($p->image_url)
      <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
    @else
      <div style="font-size:120px;line-height:1">{{ $p->category->icon ?? '⚡' }}</div>
    @endif
  </div>

  <!-- Product Actions & Information Card -->
  <div class="card product-info-card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
      <div class="product-meta-badge">
        <span>🏷️</span> {{ $p->brand }} · {{ $p->category->name ?? '' }}
      </div>
      @if($p->stock > 0)
        <span class="pill paid" style="font-size:12.5px">
          <span class="stock-dot"></span> {{ __('In stock') }} ({{ $p->stock }})
        </span>
      @else
        <span class="pill cancelled" style="font-size:12.5px">{{ __('Out of stock') }}</span>
      @endif
    </div>

    <h1 style="font-size:clamp(22px, 3vw, 32px);font-weight:800;line-height:1.25">
      {{ $p->name }}
    </h1>

    <div class="price" style="font-size:28px;gap:10px">
      <span>{{ number_format($p->price) }} {{ $cur }}</span>
      @if($p->old_price)
        <s style="font-size:18px">{{ number_format($p->old_price) }} {{ $cur }}</s>
        <span class="pill paid">-{{ $p->discount }}%</span>
      @endif
    </div>

    @if($savedAmount > 0)
      <div style="font-size:13.5px;color:var(--ok);font-weight:700">
        🎉 {{ __('Save :amount', ['amount' => number_format($savedAmount).' '.$cur]) }}
      </div>
    @endif

    <p style="color:var(--ink-secondary);line-height:1.7;margin:8px 0;font-size:15px">
      {{ $p->desc }}
    </p>

    @if($p->stock > 0)
      <form class="js-add" method="POST" action="/cart/{{ $p->id }}" style="display:flex;flex-direction:column;gap:14px;margin-top:8px">
        @csrf
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
          <label style="margin:0;font-size:14px;font-weight:700">{{ __('Quantity') }}:</label>
          <div class="qty-stepper">
            <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">−</button>
            <input type="number" name="qty" value="1" min="1" max="{{ min($p->stock, 20) }}" aria-label="{{ __('Quantity') }}" readonly>
            <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
          </div>
        </div>

        <button type="submit" class="btn lg" style="width:100%">
          🛒 {{ __('Add to cart') }}
        </button>
      </form>
    @else
      <button class="btn ghost lg" disabled style="width:100%">{{ __('Out of stock') }}</button>
    @endif

    <a class="btn whatsapp" target="_blank" rel="noopener"
      href="https://wa.me/{{ config('volt.whatsapp') }}?text={{ $waMsg }}">
      💬 {{ __('Order via WhatsApp') }}
    </a>

    <!-- Trust Perks List -->
    <div class="product-perks">
      <div class="product-perk-item">
        <span>🚚</span> <span>{{ __('Fast shipping') }} (24-48h)</span>
      </div>
      <div class="product-perk-item">
        <span>🛡️</span> <span>{{ __('1-Year Warranty') }}</span>
      </div>
      <div class="product-perk-item">
        <span>🔄</span> <span>{{ __('14-Day Returns') }}</span>
      </div>
      <div class="product-perk-item">
        <span>🔒</span> <span>{{ __('Cash on delivery') }}</span>
      </div>
    </div>
  </div>
</div>

<!-- Related Products Section -->
@if($related->count())
  <div class="sec" style="margin-top:40px">
    <h2>✨ {{ __('You may also like') }}</h2>
  </div>
  <div class="grid">
    @foreach($related as $p)
      @include('partials.card')
    @endforeach
  </div>
@endif

@endsection