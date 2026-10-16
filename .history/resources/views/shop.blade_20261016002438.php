@extends('layouts.app')
@section('title', $section==='perfume' ? __('Perfumes') : __('Electronics'))
@section('content')
<div class="shop">
  <aside class="card filters">
    <form id="filters" method="GET">
      <label>{{ __('Search') }}</label><input name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}">
      <label>{{ __('Category') }}</label>
      <select name="category"><option value="">{{ __('All') }}</option>@foreach($cats as $c)<option value="{{ $c->id }}" @selected(request('category')==$c->id)>{{ $c->icon }} {{ $c->name }}</option>@endforeach</select>
      <label>{{ __('Brand') }}</label>
      <select name="brand"><option value="">{{ __('All') }}</option>@foreach($brands as $b)<option @selected(request('brand')==$b)>{{ $b }}</option>@endforeach</select>
      <label>{{ __('Price range') }}</label>
      <div style="display:flex;gap:8px"><input type="number" name="min" min="0" value="{{ request('min') }}" placeholder="{{ __('Min') }}"><input type="number" name="max" min="0" value="{{ request('max') }}" placeholder="{{ __('Max') }}"></div>
      <label>{{ __('Sort by') }}</label>
      <select name="sort">@foreach(['new'=>'Newest','price_asc'=>'Price: low to high','price_desc'=>'Price: high to low','discount'=>'Biggest discount'] as $k=>$v)<option value="{{ $k }}" @selected(request('sort')==$k)>{{ __($v) }}</option>@endforeach</select>
      <a class="btn ghost" style="width:100%;margin-top:14px" href="{{ url()->current() }}">{{ __('Reset') }}</a>
    </form>
  </aside>
  <section>
    <div class="sec" style="margin-top:0"><h2>{{ $section==='perfume' ? '🧴 '.__('Perfumes') : __('Electronics') }}</h2><span>{{ $products->total() }} {{ __('products') }}</span></div>
    @forelse($products as $p)@if($loop->first)<div class="grid">@endif @include('partials.card') @if($loop->last)</div>@endif
    @empty<div class="card">{{ __('No products match your filters.') }}</div>@endforelse
    <div class="pager">{{ $products->links('pagination::simple-default') }}</div>
  </section>
</div>
@endsection
