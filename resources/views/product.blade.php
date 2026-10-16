@extends('layouts.app')
@section('title', $p->name)
@section('content')
<div class="two" style="grid-template-columns:1fr 1fr">
  <div class="pimg card" style="font-size:120px;padding:0">@if($p->image_url)<img src="{{ $p->image_url }}"
      alt="{{ $p->name }}">@else{{ $p->category->icon }}@endif</div>
  <div class="card">
    <small>{{ $p->brand }} · {{ $p->category->name }}</small>
    <h1 style="margin:6px 0">{{ $p->name }}</h1>
    <div class="price" style="font-size:28px">{{ number_format($p->price) }} {{ config('volt.currency') }}
      @if($p->old_price)<s>{{ number_format($p->old_price) }}</s> <span class="pill paid">-{{ $p->discount
        }}%</span>@endif</div>
    <p style="margin:16px 0;color:var(--mute)">{{ $p->desc }}</p>
    <p>{{ $p->stock>0 ? '✅ '.__('In stock').' ('.$p->stock.')' : '❌ '.__('Out of stock') }}</p>
    @if($p->stock>0)
    <form class="js-add qty" method="POST" action="/cart/{{ $p->id }}" style="margin:16px 0">@csrf
      <input type="number" name="qty" value="1" min="1" max="{{ min($p->stock,20) }}"><button class="btn">🛒 {{ __('Add
        to cart') }}</button>
    </form>
    @endif
    <a class="btn ghost" target="_blank" rel="noopener"
      href="https://wa.me/{{ config('volt.whatsapp') }}?text={{ rawurlencode(__('I want to order:').' '.$p->name_en.' — '.url('/product/'.$p->id)) }}">💬
      {{ __('Order via WhatsApp') }}</a>
  </div>
</div>
@if($related->count())<div class="sec">
  <h2>{{ __('You may also like') }}</h2>
</div>
<div class="grid">@foreach($related as $p)@include('partials.card')@endforeach</div>@endif
@endsection