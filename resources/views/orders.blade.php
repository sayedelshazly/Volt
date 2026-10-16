@extends('layouts.app')
@section('title', __('My orders'))
@section('content')

@php $cur = config('volt.currency'); @endphp

<div class="sec">
  <div>
    <h2>🧾 {{ __('My orders') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('View your order history and track shipments') }}</p>
  </div>
  <a href="/shop" class="btn ghost sm">⚡ {{ __('Shop electronics') }}</a>
</div>

@if($orders->isEmpty())
  <div class="card" style="text-align:center;padding:64px 24px">
    <div style="font-size:52px;margin-bottom:14px">📦</div>
    <h3 style="margin-bottom:8px">{{ __('No orders yet.') }}</h3>
    <p style="color:var(--mute);margin-bottom:20px;max-width:38ch;margin-inline:auto">
      {{ __('Power up your everyday') }} — {{ __('Smart watches, earbuds, mobile accessories and a dedicated perfume collection — all in one store.') }}
    </p>
    <a class="btn" href="/shop">{{ __('Start shopping') }} →</a>
  </div>
@else
  <div class="card tw" style="padding:0;overflow:hidden">
    <table>
      <thead>
        <tr>
          <th>{{ __('Order number') }}</th>
          <th>{{ __('Date') }}</th>
          <th>{{ __('Total') }}</th>
          <th>{{ __('Status') }}</th>
          <th style="text-align:end">{{ __('Action') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $o)
          <tr>
            <td>
              <b dir="ltr" style="color:var(--blue)">{{ $o->number }}</b>
              <br>
              <small style="color:var(--mute)">{{ $o->items->count() ?? 1 }} {{ __('Items') }}</small>
            </td>
            <td>
              <span>{{ $o->created_at->format('Y-m-d') }}</span>
              <br>
              <small style="color:var(--mute)">{{ $o->created_at->format('H:i') }}</small>
            </td>
            <td>
              <b style="font-weight:800">{{ number_format($o->total) }} {{ $cur }}</b>
              <br>
              <span class="pill {{ $o->payment_status }}" style="font-size:11px">
                {{ __(ucfirst($o->payment_status)) }}
              </span>
            </td>
            <td>
              <span class="pill {{ $o->status }}" style="font-size:12.5px">
                {{ __(ucfirst($o->status)) }}
              </span>
            </td>
            <td style="text-align:end">
              <a class="btn sm ghost" href="/track?number={{ $o->number }}&phone={{ $o->phone }}">
                📦 {{ __('Track') }}
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="pager">
    {{ $orders->links('pagination::simple-default') }}
  </div>
@endif

@endsection