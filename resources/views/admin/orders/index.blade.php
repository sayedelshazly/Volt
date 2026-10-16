@extends('admin.layout')
@section('title', __('Orders'))
@section('content')

@php
  $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
  $cur = config('volt.currency');
@endphp

<div class="sec" style="margin-top:0">
  <div>
    <h2>🧾 {{ __('Orders') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Monitor transactions, update delivery status, and contact buyers') }}</p>
  </div>
</div>

<!-- Filters Bar -->
<form method="GET" class="card" style="padding:16px;margin-bottom:20px">
  <div class="form2" style="align-items:center">
    <input name="q" value="{{ request('q') }}" placeholder="{{ __('Order number, phone or name') }}">
    <div style="display:flex;gap:8px">
      <select name="status" onchange="this.form.submit()">
        <option value="">{{ __('Status') }}: {{ __('All') }}</option>
        @foreach ($statuses as $s)
          <option value="{{ $s }}" @selected(request('status') === $s)>
            {{ __(ucfirst($s)) }}
          </option>
        @endforeach
      </select>
      <button type="submit" class="btn sm" style="padding-inline:18px">🔍 {{ __('Search') }}</button>
      @if(request('q') || request('status'))
        <a href="/admin/orders" class="btn ghost sm" style="color:var(--err)">✕</a>
      @endif
    </div>
  </div>
</form>

<div class="card tw" style="padding:0;overflow:hidden">
  <table>
    <thead>
      <tr>
        <th>{{ __('Order number') }}</th>
        <th>{{ __('Customer') }}</th>
        <th>{{ __('Total') }}</th>
        <th>{{ __('Payment') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Date') }}</th>
        <th style="text-align:end">{{ __('Action') }}</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($orders as $o)
        <tr>
          <td>
            <a dir="ltr" href="/admin/orders/{{ $o->id }}" style="font-weight:800;color:var(--blue)">
              {{ $o->number }}
            </a>
          </td>
          <td>
            <b>{{ $o->name }}</b>
            <br>
            <small dir="ltr" style="color:var(--mute)">{{ $o->phone }}</small>
          </td>
          <td>
            <b>{{ number_format($o->total) }} {{ $cur }}</b>
          </td>
          <td>
            <span class="pill {{ $o->payment_status }}">
              {{ __(ucfirst($o->payment_status)) }}
            </span>
          </td>
          <td>
            <span class="pill {{ $o->status }}" style="font-size:12.5px">
              {{ __(ucfirst($o->status)) }}
            </span>
          </td>
          <td>
            <span>{{ $o->created_at->format('Y-m-d') }}</span>
            <br>
            <small style="color:var(--mute)">{{ $o->created_at->format('H:i') }}</small>
          </td>
          <td style="text-align:end">
            <a class="btn sm ghost" href="/admin/orders/{{ $o->id }}">
              {{ __('View') }} →
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:36px;color:var(--mute)">
            {{ __('No orders yet.') }}
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="pager">
  {{ $orders->links('pagination::simple-default') }}
</div>

@endsection