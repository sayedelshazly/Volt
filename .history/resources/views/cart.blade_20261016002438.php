@extends('layouts.app')
@section('title', __('Cart'))
@section('content')
@php $sub=$items->sum('sub'); $fee=\App\Http\Controllers\CartController::fee($sub); $cur=config('volt.currency'); @endphp
<div class="sec"><h2>🛒 {{ __('Cart') }}</h2></div>
@if($items->isEmpty())<div class="card">{{ __('Your cart is empty.') }} <a class="btn sm" href="/shop">{{ __('Start shopping') }}</a></div>
@else
<div class="two">
  <div class="card tw"><table>
    @foreach($items as $i)<tr>
      <td>{{ $i->p->category->icon ?? '' }} <a href="/product/{{ $i->p->id }}"><b>{{ $i->p->name }}</b></a><br><small>{{ number_format($i->p->price) }} {{ $cur }}</small></td>
      <td><form method="POST" action="/cart/{{ $i->p->id }}" class="qty">@csrf @method('PATCH')<input type="number" name="qty" value="{{ $i->qty }}" min="0" max="{{ min($i->p->stock,20) }}"><button class="btn ghost sm">✓</button></form></td>
      <td><b>{{ number_format($i->sub) }} {{ $cur }}</b></td>
      <td><form method="POST" action="/cart/{{ $i->p->id }}">@csrf @method('DELETE')<button class="btn danger sm" aria-label="{{ __('Remove') }}">✕</button></form></td></tr>@endforeach
  </table></div>
  <div class="card" style="align-self:start">
    <p style="display:flex;justify-content:space-between">{{ __('Subtotal') }}<b>{{ number_format($sub) }} {{ $cur }}</b></p>
    <p style="display:flex;justify-content:space-between">{{ __('Shipping') }}<b>{{ $fee ? number_format($fee).' '.$cur : __('Free') }}</b></p><hr style="border-color:var(--line);margin:10px 0">
    <p style="display:flex;justify-content:space-between;font-size:20px">{{ __('Total') }}<b class="price">{{ number_format($sub+$fee) }} {{ $cur }}</b></p>
    <a class="btn" style="width:100%;margin-top:14px" href="/checkout">{{ __('Checkout') }}</a>
  </div>
</div>@endif
@endsection
