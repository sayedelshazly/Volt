@extends('layouts.app')
@section('title', __('Checkout'))
@section('content')
@php $sub=$items->sum('sub'); $fee=\App\Http\Controllers\CartController::fee($sub); $cur=config('volt.currency'); $u=auth()->user(); $card=old('payment_method')==='card'; @endphp
<div class="sec"><h2>{{ __('Checkout') }}</h2></div>
@error('cart')<div class="flash err">{{ $message }}</div>@enderror
<form method="POST" action="/checkout" class="two" novalidate>@csrf
  <div class="card"><div class="form2">
    <div><label>{{ __('Full name') }}</label><input name="name" value="{{ old('name',$u->name??'') }}" required>@error('name')<div class="err">{{ $message }}</div>@enderror</div>
    <div><label>{{ __('Phone') }}</label><input name="phone" dir="ltr" value="{{ old('phone',$u->phone??'') }}" required>@error('phone')<div class="err">{{ $message }}</div>@enderror</div>
    <div><label>{{ __('City') }}</label><input name="city" value="{{ old('city') }}" required>@error('city')<div class="err">{{ $message }}</div>@enderror</div>
    <div><label>{{ __('Notes') }}</label><input name="notes" value="{{ old('notes') }}"></div>
  </div>
  <label>{{ __('Address') }}</label><textarea name="address" rows="2" required>{{ old('address') }}</textarea>@error('address')<div class="err">{{ $message }}</div>@enderror
  <label>{{ __('Payment method') }}</label>
  <label style="font-weight:500"><input type="radio" name="payment_method" value="cod" style="width:auto" @checked(!$card)> {{ __('Cash on delivery') }}</label>
  <label style="font-weight:500"><input type="radio" name="payment_method" value="card" style="width:auto" @checked($card)> {{ __('Credit card (test gateway)') }}</label>
  <div id="cardBox" @if(!$card) hidden @endif class="card" style="margin-top:10px;box-shadow:none">
    <small>{{ __('Test card: 4242 4242 4242 4242 — any future date — any CVC') }}</small>
    <label>{{ __('Card number') }}</label><input id="card_number" name="card_number" dir="ltr" inputmode="numeric" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242">@error('card_number')<div class="err">{{ $message }}</div>@enderror
    <div class="form2"><div><label>MM/YY</label><input id="card_exp" name="card_exp" dir="ltr" value="{{ old('card_exp') }}" placeholder="12/30">@error('card_exp')<div class="err">{{ $message }}</div>@enderror</div>
    <div><label>CVC</label><input name="card_cvc" dir="ltr" inputmode="numeric" maxlength="4" placeholder="123">@error('card_cvc')<div class="err">{{ $message }}</div>@enderror</div></div>
  </div></div>
  <div class="card" style="align-self:start"><h3>{{ __('Order summary') }}</h3>
    @foreach($items as $i)<p style="display:flex;justify-content:space-between"><span>{{ $i->p->name }} × {{ $i->qty }}</span><span>{{ number_format($i->sub) }}</span></p>@endforeach<hr style="border-color:var(--line);margin:10px 0">
    <p style="display:flex;justify-content:space-between">{{ __('Shipping') }}<b>{{ $fee ? number_format($fee) : __('Free') }}</b></p>
    <p style="display:flex;justify-content:space-between;font-size:20px">{{ __('Total') }}<b class="price">{{ number_format($sub+$fee) }} {{ $cur }}</b></p>
    <button class="btn" style="width:100%;margin-top:14px">{{ __('Place order') }}</button></div>
</form>
@endsection
