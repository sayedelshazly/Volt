@extends('layouts.app')
@section('content')
<section class="hero">
  <div>
    <h1>{{ __('Power up your everyday') }}</h1>
    <p>{{ __('Smart watches, earbuds, mobile accessories and a dedicated perfume collection — all in one store.') }}</p>
    <div class="btns"><a class="btn" href="/shop">{{ __('Shop electronics') }}</a> <a class="btn ghost"
        href="/perfumes">{{ __('Explore perfumes') }}</a></div>
  </div>
  <div class="orbs">
    <div class="orb o1">⌚</div>
    <div class="orb o2">🎧</div>
    <div class="orb o3">🧴</div>
  </div>
</section>
<div class="cats">@foreach($cats as $c)<a class="cat" data-aos="zoom-in" href="/shop?category={{ $c->id }}"><i>{{
      $c->icon }}</i>{{ $c->name }}</a>@endforeach</div>
<div class="sec">
  <h2>{{ __('Featured products') }}</h2><a href="/shop" class="btn ghost sm">{{ __('View all') }}</a>
</div>
<div class="grid">@foreach($featured as $p)@include('partials.card')@endforeach</div>
@if($perfumes->count())
<section class="perf" data-aos="fade-up">
  <div class="sec" style="margin-top:0">
    <h2>🧴 {{ __('Perfume collection') }}</h2><a href="/perfumes" class="btn sm"
      style="background:#fff;color:#4b2bb8">{{ __('View all') }}</a>
  </div>
  <div class="grid">@foreach($perfumes as $p)@include('partials.card')@endforeach</div>
</section>@endif
@endsection