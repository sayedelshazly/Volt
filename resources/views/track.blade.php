@extends('layouts.app')
@section('title', __('Track order'))
@section('content')
@php $cur=config('volt.currency'); @endphp
<div class="sec">
  <h2>📦 {{ __('Track order') }}</h2>
</div>
<form class="card form2" method="GET" action="/track">
  <div><label>{{ __('Order number') }}</label><input name="number" dir="ltr" value="{{ request('number') }}"
      placeholder="VLT-XXXXXXXX" required></div>
  <div><label>{{ __('Phone') }}</label><input name="phone" dir="ltr" value="{{ request('phone') }}" required></div>
  <div><button class="btn" style="margin-top:12px">{{ __('Track') }}</button></div>
</form>
@if($order)
<div class="card" style="margin-top:20px">
  <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px">
    <h3 dir="ltr">{{ $order->number }}</h3><span class="pill {{ $order->status }}">{{ __(ucfirst($order->status))
      }}</span>
  </div>
  @if($order->status==='cancelled')<div class="flash err">{{ __('This order was cancelled.') }}</div>
  @else @php $idx=array_search($order->status,\App\Models\Order::FLOW); $ic=['🕓','⚙️','🚚','✅']; @endphp
  <div class="tl">@foreach(\App\Models\Order::FLOW as $k=>$s)<div class="st {{ $k<=$idx?'done':'' }}"><b>{{ $ic[$k]
        }}</b><small>{{ __(ucfirst($s)) }}</small></div>@endforeach</div>@endif
  <div class="tw">
    <table>@foreach($order->items as $i)<tr>
        <td>{{ $i->name }} × {{ $i->qty }}</td>
        <td>{{ number_format($i->price*$i->qty) }} {{ $cur }}</td>
      </tr>@endforeach
      <tr>
        <td>{{ __('Shipping') }}</td>
        <td>{{ number_format($order->shipping) }} {{ $cur }}</td>
      </tr>
      <tr>
        <th>{{ __('Total') }}</th>
        <th>{{ number_format($order->total) }} {{ $cur }}</th>
      </tr>
    </table>
  </div>
  <p style="margin-top:10px">{{ __('Payment') }}: <span class="pill {{ $order->payment_status }}">{{
      __(ucfirst($order->payment_status)) }}</span> · {{ $order->city }}</p>
</div>@endif
@endsection