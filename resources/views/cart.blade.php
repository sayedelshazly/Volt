@extends('layouts.app')
@section('title', __('Cart'))
@section('content')

@php
  $sub = $items->sum('sub');
  $fee = \App\Http\Controllers\CartController::fee($sub);
  $cur = config('volt.currency');
  $freeThreshold = (float) config('volt.free_shipping_over', 1500);
  $remainingForFree = max(0, $freeThreshold - $sub);
  $progressPct = min(100, round(($sub / $freeThreshold) * 100));
@endphp

<div class="sec">
  <div>
    <h2>🛒 {{ __('Cart') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ $items->count() }} {{ __('Items') }}</p>
  </div>
  <a href="/shop" class="btn ghost sm">← {{ __('Start shopping') }}</a>
</div>

@if($items->isEmpty())
  <div class="card" style="text-align:center;padding:64px 24px">
    <div style="font-size:56px;margin-bottom:14px">🛒</div>
    <h3 style="margin-bottom:8px">{{ __('Your cart is empty.') }}</h3>
    <p style="color:var(--mute);margin-bottom:24px;max-width:38ch;margin-inline:auto">
      {{ __('Power up your everyday') }} — {{ __('Smart watches, earbuds, mobile accessories and a dedicated perfume collection — all in one store.') }}
    </p>
    <a class="btn" href="/shop">{{ __('Start shopping') }} →</a>
  </div>
@else

  <!-- Free Shipping Progress Indicator -->
  <div class="free-shipping-card">
    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13.5px;font-weight:700">
      @if($remainingForFree > 0)
        <span>🚚 {{ __('Add :amount more to get Free Shipping!', ['amount' => number_format($remainingForFree).' '.$cur]) }}</span>
        <span style="color:var(--blue)">{{ $progressPct }}%</span>
      @else
        <span style="color:var(--ok)">🎉 {{ __('You unlocked Free Shipping!') }}</span>
        <span style="color:var(--ok)">100%</span>
      @endif
    </div>
    <div class="free-shipping-bar">
      <div class="free-shipping-progress" style="width: {{ $progressPct }}%"></div>
    </div>
  </div>

  <div class="two">
    <!-- Cart Items Container -->
    <div>
      <div class="card tw" style="padding:0;overflow:hidden">
        <table>
          <thead>
            <tr>
              <th>{{ __('Product') }}</th>
              <th style="text-align:center">{{ __('Quantity') }}</th>
              <th style="text-align:end">{{ __('Subtotal') }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $i)
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:12px">
                    <a href="/product/{{ $i->p->id }}" style="width:54px;height:54px;border-radius:10px;background:var(--surface-alt);display:grid;place-items:center;flex-shrink:0;overflow:hidden">
                      @if($i->p->image_url)
                        <img src="{{ $i->p->image_url }}" alt="{{ $i->p->name }}" style="width:100%;height:100%;object-fit:cover">
                      @else
                        <span style="font-size:24px">{{ $i->p->category->icon ?? '⚡' }}</span>
                      @endif
                    </a>
                    <div>
                      <a href="/product/{{ $i->p->id }}" style="font-weight:700;font-size:14.5px;display:block">
                        {{ $i->p->name }}
                      </a>
                      <small style="color:var(--mute)">{{ number_format($i->p->price) }} {{ $cur }}</small>
                    </div>
                  </div>
                </td>

                <td style="text-align:center">
                  <form method="POST" action="/cart/{{ $i->p->id }}" class="qty-auto-submit" style="display:inline-flex;align-items:center">
                    @csrf
                    @method('PATCH')
                    <div class="qty-stepper">
                      <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">−</button>
                      <input type="number" name="qty" value="{{ $i->qty }}" min="1" max="{{ min($i->p->stock, 20) }}" aria-label="{{ __('Quantity') }}" readonly>
                      <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
                    </div>
                  </form>
                </td>

                <td style="text-align:end;white-space:nowrap">
                  <b style="font-weight:800;color:var(--blue)">{{ number_format($i->sub) }} {{ $cur }}</b>
                </td>

                <td style="text-align:end">
                  <form method="POST" action="/cart/{{ $i->p->id }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger sm" aria-label="{{ __('Remove') }}" title="{{ __('Remove') }}" style="padding:6px 10px">
                      ✕
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Order Summary Sticky Card -->
    <div class="card" style="position:sticky;top:calc(var(--header-h) + 16px);align-self:start">
      <h3 style="font-size:18px;font-weight:800;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--line)">
        🧾 {{ __('Order summary') }}
      </h3>

      <div style="display:flex;flex-direction:column;gap:12px">
        <p style="display:flex;justify-content:space-between;color:var(--ink-secondary)">
          <span>{{ __('Subtotal') }}</span>
          <b>{{ number_format($sub) }} {{ $cur }}</b>
        </p>

        <p style="display:flex;justify-content:space-between;color:var(--ink-secondary)">
          <span>{{ __('Shipping') }}</span>
          @if($fee > 0)
            <b>{{ number_format($fee) }} {{ $cur }}</b>
          @else
            <b style="color:var(--ok)">{{ __('Free') }} 🎉</b>
          @endif
        </p>

        <hr style="border:0;border-top:1px solid var(--line);margin:4px 0">

        <p style="display:flex;justify-content:space-between;align-items:baseline;font-size:18px">
          <span style="font-weight:700">{{ __('Total') }}</span>
          <b class="price" style="font-size:24px">{{ number_format($sub + $fee) }} {{ $cur }}</b>
        </p>
      </div>

      <a class="btn lg" style="width:100%;margin-top:20px" href="/checkout">
        🔒 {{ __('Checkout') }} →
      </a>

      <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px;font-size:12.5px;color:var(--mute)">
        <div style="display:flex;align-items:center;gap:6px">
          <span>🛡️</span> {{ __('Secure Checkout') }}
        </div>
        <div style="display:flex;align-items:center;gap:6px">
          <span>💵</span> {{ __('Cash on delivery') }}
        </div>
      </div>
    </div>
  </div>

@endif

@endsection