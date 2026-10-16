@extends('admin.layout')
@section('title', $o->number)
@section('content')
@php
  $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
  $payments = ['unpaid', 'paid', 'failed', 'refunded'];
  $wa = 'https://wa.me/' . preg_replace('/\D/', '', $o->phone) . '?text=' . rawurlencode(__('Hello') . ' ' . $o->name . ', ' . __('about your order') . ' ' . $o->number);
  $cur = config('volt.currency');
@endphp

<div class="sec" style="margin-top:0">
  <div>
    <h2 dir="ltr" style="color:var(--blue)">{{ $o->number }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ $o->created_at->format('Y-m-d H:i') }}</p>
  </div>
  <a href="/admin/orders" class="btn ghost sm">← {{ __('Orders') }}</a>
</div>

<div class="two">
  <!-- Order Details & Items Card -->
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;flex-wrap:wrap;gap:8px">
      <div>
        <h3 style="font-size:18px;font-weight:800">{{ $o->name }}</h3>
        <div dir="ltr" style="color:var(--mute);font-size:14px">{{ $o->phone }}</div>
        <div style="color:var(--ink-secondary);font-size:14px;margin-top:4px">{{ $o->city }}: {{ $o->address }}</div>
        @if ($o->notes)
          <div style="margin-top:8px;padding:8px 12px;background:var(--surface-alt);border-radius:8px;font-size:13px">
            📝 <b>{{ __('Notes') }}:</b> {{ $o->notes }}
          </div>
        @endif
      </div>

      <a class="btn whatsapp sm" target="_blank" rel="noopener" href="{{ $wa }}">
        💬 {{ __('Contact customer') }}
      </a>
    </div>

    <div class="tw" style="margin:16px 0;border:1px solid var(--line);border-radius:12px;overflow:hidden">
      <table>
        <thead>
          <tr>
            <th>{{ __('Item') }}</th>
            <th style="text-align:end">{{ __('Subtotal') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($o->items as $i)
            <tr>
              <td>
                <b>{{ $i->name }}</b> × {{ $i->qty }}
                <br>
                <small style="color:var(--mute)">{{ number_format($i->price) }} {{ $cur }}</small>
              </td>
              <td style="text-align:end;font-weight:700">
                {{ number_format($i->price * $i->qty) }} {{ $cur }}
              </td>
            </tr>
          @endforeach
          <tr>
            <td style="color:var(--mute)">{{ __('Shipping') }}</td>
            <td style="text-align:end;font-weight:700">{{ number_format($o->shipping) }} {{ $cur }}</td>
          </tr>
          <tr style="background:var(--surface-alt)">
            <th style="font-size:16px">{{ __('Total') }}</th>
            <th style="text-align:end;font-size:18px;color:var(--blue)">
              {{ number_format($o->total) }} {{ $cur }}
            </th>
          </tr>
        </tbody>
      </table>
    </div>

    <div style="display:flex;align-items:center;gap:12px;font-size:13.5px;color:var(--mute)">
      <span>{{ $o->payment_method === 'card' ? '💳 ' . ($o->transaction_id ?? 'Card Gateway') : '💵 ' . __('Cash on delivery') }}</span>
    </div>
  </div>

  <!-- Status Update Action Card -->
  <form class="card" style="align-self:start" method="POST" action="/admin/orders/{{ $o->id }}">
    @csrf
    @method('PATCH')

    <h3 style="font-size:16px;font-weight:700;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--line)">
      ⚡ {{ __('Status') }}
    </h3>

    <label for="order_status">{{ __('Status') }}</label>
    <select id="order_status" name="status">
      @foreach ($statuses as $s)
        <option value="{{ $s }}" @selected($o->status === $s)>{{ __(ucfirst($s)) }}</option>
      @endforeach
    </select>

    <label for="payment_status">{{ __('Payment') }}</label>
    <select id="payment_status" name="payment_status">
      @foreach ($payments as $s)
        <option value="{{ $s }}" @selected($o->payment_status === $s)>{{ __(ucfirst($s)) }}</option>
      @endforeach
    </select>

    <button type="submit" class="btn" style="width:100%;margin-top:18px">
      {{ __('Save') }}
    </button>
  </form>
</div>
@endsection