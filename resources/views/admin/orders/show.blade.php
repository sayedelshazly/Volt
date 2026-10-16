@extends('admin.layout')
@section('title', $o->number)
@section('content')
@php
$statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
$payments = ['unpaid', 'paid', 'failed', 'refunded'];
$wa = 'https://wa.me/' . preg_replace('/\D/', '', $o->phone) . '?text=' . rawurlencode(__('Hello') . ' ' . $o->name . ',
' . __('about your order') . ' ' . $o->number);
@endphp
<h2 dir="ltr" style="margin-bottom:14px">{{ $o->number }}</h2>
<div class="two" style="grid-template-columns:1.3fr 1fr">
    <div class="card">
        <p><b>{{ $o->name }}</b> — <span dir="ltr">{{ $o->phone }}</span></p>
        <p>{{ $o->city }}: {{ $o->address }}</p>
        @if ($o->notes)<p>📝 {{ $o->notes }}</p>@endif
        <div class="tw">
            <table style="margin-top:12px">
                @foreach ($o->items as $i)
                <tr>
                    <td>{{ $i->name }} × {{ $i->qty }}</td>
                    <td>{{ number_format($i->price * $i->qty) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>{{ __('Shipping') }}</td>
                    <td>{{ number_format($o->shipping) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Total') }}</th>
                    <th>{{ number_format($o->total) }} {{ config('volt.currency') }}</th>
                </tr>
            </table>
        </div>
        <p style="margin-top:10px">{{ $o->payment_method === 'card' ? '💳 ' . $o->transaction_id : '💵 ' . __('Cash on
            delivery') }}</p>
        <a class="btn ghost sm" target="_blank" rel="noopener" href="{{ $wa }}">💬 {{ __('Contact customer') }}</a>
    </div>
    <form class="card" style="align-self:start" method="POST" action="/admin/orders/{{ $o->id }}">
        @csrf
        @method('PATCH')
        <label>{{ __('Status') }}</label>
        <select name="status">
            @foreach ($statuses as $s)
            <option value="{{ $s }}" @if ($o->status === $s) selected @endif>{{ __(ucfirst($s)) }}</option>
            @endforeach
        </select>
        <label>{{ __('Payment') }}</label>
        <select name="payment_status">
            @foreach ($payments as $s)
            <option value="{{ $s }}" @if ($o->payment_status === $s) selected @endif>{{ __(ucfirst($s)) }}</option>
            @endforeach
        </select>
        <button class="btn" style="width:100%;margin-top:14px">{{ __('Save') }}</button>
    </form>
</div>
@endsection