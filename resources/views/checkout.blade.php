@extends('layouts.app')
@section('title', __('Checkout'))
@section('content')

@php
  $sub = $items->sum('sub');
  $fee = \App\Http\Controllers\CartController::fee($sub);
  $cur = config('volt.currency');
  $u = auth()->user();
  $card = old('payment_method') === 'card';
@endphp

<div class="sec">
  <div>
    <h2>🔒 {{ __('Checkout') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Delivery details') }} & {{ __('Payment options') }}</p>
  </div>
  <a href="/cart" class="btn ghost sm">← {{ __('Cart') }}</a>
</div>

@error('cart')
  <div class="flash err">
    <span>⚠️</span> {{ $message }}
  </div>
@enderror

<form method="POST" action="/checkout" class="two" novalidate>
  @csrf

  <!-- Left Column: Delivery & Payment Details -->
  <div style="display:flex;flex-direction:column;gap:20px">
    <!-- Step 1: Delivery Details -->
    <div class="card">
      <h3 style="font-size:18px;font-weight:800;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px">
        <span>📍</span> {{ __('Delivery details') }}
      </h3>

      <div class="form2">
        <div>
          <label for="name">{{ __('Full name') }} <span style="color:var(--err)">*</span></label>
          <input id="name" name="name" value="{{ old('name', $u->name ?? '') }}" placeholder="{{ __('Full name') }}" required autocomplete="name">
          @error('name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label for="phone">{{ __('Phone') }} <span style="color:var(--err)">*</span></label>
          <input id="phone" name="phone" dir="ltr" type="tel" value="{{ old('phone', $u->phone ?? '') }}" placeholder="01XXXXXXXXX" required autocomplete="tel">
          @error('phone')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label for="city">{{ __('City') }} <span style="color:var(--err)">*</span></label>
          <input id="city" name="city" value="{{ old('city') }}" placeholder="{{ __('City') }}" required autocomplete="address-level2">
          @error('city')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div>
          <label for="notes">{{ __('Notes') }} ({{ __('Optional') }})</label>
          <input id="notes" name="notes" value="{{ old('notes') }}" placeholder="{{ __('Notes') }}">
        </div>
      </div>

      <div style="margin-top:12px">
        <label for="address">{{ __('Address') }} <span style="color:var(--err)">*</span></label>
        <textarea id="address" name="address" rows="2" placeholder="{{ __('Address') }}..." required autocomplete="street-address">{{ old('address') }}</textarea>
        @error('address')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <!-- Step 2: Payment Method -->
    <div class="card">
      <h3 style="font-size:18px;font-weight:800;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px">
        <span>💳</span> {{ __('Payment method') }}
      </h3>

      <div class="payment-methods-grid">
        <label class="payment-card-label">
          <input type="radio" name="payment_method" value="cod" @checked(!$card)>
          <div class="payment-card-content">
            <div style="font-weight:700">💵 {{ __('Cash on delivery') }}</div>
            <small style="color:var(--mute)">{{ __('Pay when your order arrives') }}</small>
          </div>
        </label>

        <label class="payment-card-label">
          <input type="radio" name="payment_method" value="card" @checked($card)>
          <div class="payment-card-content">
            <div style="font-weight:700">💳 {{ __('Credit card (test gateway)') }}</div>
            <small style="color:var(--mute)">Visa / MasterCard</small>
          </div>
        </label>
      </div>

      <div id="cardBox" @if(!$card) hidden @endif class="card" style="margin-top:16px;background:var(--surface-alt);border-color:var(--line)">
        <div style="font-size:13px;color:var(--blue);font-weight:600;margin-bottom:12px;display:flex;align-items:center;gap:6px">
          <span>ℹ️</span> {{ __('Test card: 4242 4242 4242 4242 — any future date — any CVC') }}
        </div>

        <div>
          <label for="card_number">{{ __('Card number') }}</label>
          <input id="card_number" name="card_number" dir="ltr" inputmode="numeric" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242" autocomplete="cc-number">
          @error('card_number')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="form2" style="margin-top:8px">
          <div>
            <label for="card_exp">MM/YY</label>
            <input id="card_exp" name="card_exp" dir="ltr" value="{{ old('card_exp') }}" placeholder="12/30" autocomplete="cc-exp">
            @error('card_exp')<div class="err">{{ $message }}</div>@enderror
          </div>

          <div>
            <label for="card_cvc">CVC</label>
            <input id="card_cvc" name="card_cvc" dir="ltr" inputmode="numeric" maxlength="4" placeholder="123" autocomplete="cc-csc">
            @error('card_cvc')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: Order Summary -->
  <div class="card" style="position:sticky;top:calc(var(--header-h) + 16px);align-self:start">
    <h3 style="font-size:18px;font-weight:800;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--line)">
      🧾 {{ __('Order summary') }}
    </h3>

    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;max-height:240px;overflow-y:auto;padding-inline-end:4px">
      @foreach($items as $i)
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:14px;color:var(--ink-secondary)">
          <span>{{ $i->p->name }} <b style="color:var(--ink)">× {{ $i->qty }}</b></span>
          <b>{{ number_format($i->sub) }} {{ $cur }}</b>
        </div>
      @endforeach
    </div>

    <hr style="border:0;border-top:1px solid var(--line);margin:8px 0 14px">

    <div style="display:flex;flex-direction:column;gap:10px">
      <p style="display:flex;justify-content:space-between;color:var(--ink-secondary);font-size:14.5px">
        <span>{{ __('Subtotal') }}</span>
        <b>{{ number_format($sub) }} {{ $cur }}</b>
      </p>

      <p style="display:flex;justify-content:space-between;color:var(--ink-secondary);font-size:14.5px">
        <span>{{ __('Shipping') }}</span>
        @if($fee > 0)
          <b>{{ number_format($fee) }} {{ $cur }}</b>
        @else
          <b style="color:var(--ok)">{{ __('Free') }} 🎉</b>
        @endif
      </p>

      <hr style="border:0;border-top:1px solid var(--line);margin:4px 0">

      <p style="display:flex;justify-content:space-between;align-items:baseline">
        <span style="font-weight:700;font-size:16px">{{ __('Total') }}</span>
        <b class="price" style="font-size:24px">{{ number_format($sub + $fee) }} {{ $cur }}</b>
      </p>
    </div>

    <button type="submit" class="btn lg" style="width:100%;margin-top:22px">
      🔒 {{ __('Place order') }}
    </button>

    <div style="margin-top:16px;display:flex;flex-direction:column;gap:6px;font-size:12px;color:var(--mute);text-align:center">
      <span>🛡️ {{ __('Secure Checkout') }} • 256-Bit SSL Encryption</span>
      <span>🚚 {{ __('Fast shipping') }} (24-48h)</span>
    </div>
  </div>
</form>

@endsection