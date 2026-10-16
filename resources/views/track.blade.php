@extends('layouts.app')
@section('title', __('Track order'))
@section('content')

@php $cur = config('volt.currency'); @endphp

<div class="sec">
  <div>
    <h2>📦 {{ __('Track order') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Track your package live with your order number and phone') }}</p>
  </div>
</div>

<!-- Tracking Search Form -->
<form class="card" method="GET" action="/track" style="margin-bottom:28px">
  <div class="form2">
    <div>
      <label for="track_number">{{ __('Order number') }}</label>
      <input id="track_number" name="number" dir="ltr" value="{{ request('number') }}" placeholder="VLT-XXXXXXXX" required>
    </div>
    <div>
      <label for="track_phone">{{ __('Phone') }}</label>
      <input id="track_phone" name="phone" dir="ltr" type="tel" value="{{ request('phone') }}" placeholder="01XXXXXXXXX" required>
    </div>
  </div>
  <button type="submit" class="btn" style="margin-top:16px;min-width:140px">
    🔍 {{ __('Track') }}
  </button>
</form>

@if(request('number') && !$order)
  <div class="flash err card" style="text-align:center;padding:24px">
    <span>⚠️</span> {{ __('Order not found') }}
  </div>
@endif

@if($order)
  @php
    $idx = array_search($order->status, \App\Models\Order::FLOW);
    $ic = ['🕓', '⚙️', '🚚', '✅'];
    $stepNames = ['Pending', 'Processing', 'Shipped', 'Delivered'];
    $waHelp = 'https://wa.me/' . config('volt.whatsapp') . '?text=' . rawurlencode(__('Hello VOLT, I have a question about my order') . ' ' . $order->number);
  @endphp

  <div class="card" style="margin-top:24px">
    <!-- Order Card Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;padding-bottom:16px;border-bottom:1px solid var(--line)">
      <div>
        <span style="font-size:12px;color:var(--mute);text-transform:uppercase;font-weight:700">{{ __('Order number') }}</span>
        <h3 dir="ltr" style="font-size:22px;font-weight:800;color:var(--blue);letter-spacing:0.5px">{{ $order->number }}</h3>
        <small style="color:var(--mute)">{{ $order->created_at->format('Y-m-d H:i') }}</small>
      </div>

      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <span class="pill {{ $order->status }}" style="font-size:14px;padding:6px 16px">
          {{ __(ucfirst($order->status)) }}
        </span>
        <a class="btn ghost sm" target="_blank" rel="noopener" href="{{ $waHelp }}">
          💬 {{ __('Need help?') }}
        </a>
      </div>
    </div>

    <!-- Stepper Timeline -->
    @if($order->status === 'cancelled')
      <div class="flash err" style="margin:24px 0">
        <span>❌</span> {{ __('This order was cancelled.') }}
      </div>
    @else
      <div class="tl">
        @foreach(\App\Models\Order::FLOW as $k => $s)
          <div class="st {{ $k <= $idx ? 'done' : '' }}">
            <b>{{ $ic[$k] }}</b>
            <small>{{ __(ucfirst($s)) }}</small>
          </div>
        @endforeach
      </div>
    @endif

    <!-- Order Items & Cost Details -->
    <div class="tw" style="margin:20px 0;border:1px solid var(--line);border-radius:12px;overflow:hidden">
      <table>
        <thead>
          <tr>
            <th>{{ __('Item') }}</th>
            <th style="text-align:end">{{ __('Subtotal') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $i)
            <tr>
              <td>
                <b>{{ $i->name }}</b>
                <br>
                <small style="color:var(--mute)">{{ number_format($i->price) }} {{ $cur }} × {{ $i->qty }}</small>
              </td>
              <td style="text-align:end;font-weight:700">
                {{ number_format($i->price * $i->qty) }} {{ $cur }}
              </td>
            </tr>
          @endforeach
          <tr>
            <td style="color:var(--mute)">{{ __('Shipping') }}</td>
            <td style="text-align:end;font-weight:700">
              {{ $order->shipping > 0 ? number_format($order->shipping).' '.$cur : __('Free') }}
            </td>
          </tr>
          <tr style="background:var(--surface-alt)">
            <th style="font-size:16px">{{ __('Total') }}</th>
            <th style="text-align:end;font-size:18px;color:var(--blue)">
              {{ number_format($order->total) }} {{ $cur }}
            </th>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Delivery & Payment Meta Info -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:14px;padding:16px;background:var(--surface-alt);border-radius:12px">
      <div>
        <span style="font-size:12px;color:var(--mute);display:block">{{ __('Customer') }}</span>
        <b>{{ $order->name }}</b>
        <div dir="ltr" style="font-size:13px;color:var(--mute)">{{ $order->phone }}</div>
      </div>

      <div>
        <span style="font-size:12px;color:var(--mute);display:block">{{ __('Address') }}</span>
        <b>{{ $order->city }}</b>
        <div style="font-size:13px;color:var(--mute)">{{ $order->address }}</div>
      </div>

      <div>
        <span style="font-size:12px;color:var(--mute);display:block">{{ __('Payment') }}</span>
        <span class="pill {{ $order->payment_status }}">{{ __(ucfirst($order->payment_status)) }}</span>
        <div style="font-size:13px;color:var(--mute);margin-top:4px">
          {{ $order->payment_method === 'card' ? '💳 ' . __('Credit card (test gateway)') : '💵 ' . __('Cash on delivery') }}
        </div>
      </div>
    </div>
  </div>
@endif

@endsection