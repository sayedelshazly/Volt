@extends('layouts.app')
@section('title', $section==='perfume' ? __('Perfumes') : __('Electronics'))
@section('content')

@php
  $hasFilters = request('q') || request('category') || request('brand') || request('min') || request('max') || request('sort');
  $activeCount = (request('q')?1:0) + (request('category')?1:0) + (request('brand')?1:0) + (request('min')?1:0) + (request('max')?1:0);
@endphp

<div class="sec" style="margin-top:20px;flex-wrap:wrap">
  <div>
    <h2>{{ $section==='perfume' ? '🧴 '.__('Perfumes') : '⚡ '.__('Electronics') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ $products->total() }} {{ __('products') }}</p>
  </div>

  <!-- Mobile Filter Toggle Button -->
  <div style="display:flex;gap:8px" class="filters-mobile-bar">
    <button type="button" id="mobileFilterToggle" class="btn ghost sm" style="display:flex;align-items:center;gap:6px">
      ⚙️ {{ __('Filter & Sort') }}
      @if($activeCount > 0)
        <span class="badge" style="position:static;min-width:18px;height:18px">{{ $activeCount }}</span>
      @endif
    </button>
  </div>
</div>

<!-- Active Filter Badges -->
@if($hasFilters)
  <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:20px">
    <span style="font-size:13px;font-weight:700;color:var(--mute)">{{ __('Active filters') }}:</span>
    @if(request('q'))
      <a href="{{ request()->fullUrlWithoutQuery('q') }}" class="pill" title="{{ __('Remove') }}">🔍 "{{ request('q') }}" ✕</a>
    @endif
    @if(request('category'))
      @php $catObj = $cats->firstWhere('id', request('category')); @endphp
      <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="pill" title="{{ __('Remove') }}">
        {{ $catObj->icon ?? '' }} {{ $catObj->name ?? __('Category') }} ✕
      </a>
    @endif
    @if(request('brand'))
      <a href="{{ request()->fullUrlWithoutQuery('brand') }}" class="pill" title="{{ __('Remove') }}">{{ request('brand') }} ✕</a>
    @endif
    @if(request('min') || request('max'))
      <a href="{{ request()->fullUrlWithoutQuery(['min', 'max']) }}" class="pill" title="{{ __('Remove') }}">
        {{ request('min', 0) }} - {{ request('max', '∞') }} {{ config('volt.currency') }} ✕
      </a>
    @endif
    <a href="{{ url()->current() }}" class="btn ghost sm" style="padding:3px 10px;font-size:12px;color:var(--err)">{{ __('Clear all') }}</a>
  </div>
@endif

<div class="shop">
  <!-- Filters Sidebar -->
  <aside class="card filters">
    <div class="filters-header">
      <span class="filters-title">⚙️ {{ __('Filters') }}</span>
      @if($hasFilters)
        <a href="{{ url()->current() }}" style="font-size:12.5px;color:var(--mute)">{{ __('Reset') }}</a>
      @endif
    </div>

    <form id="filters" method="GET">
      <label for="filter-q">{{ __('Search') }}</label>
      <input id="filter-q" name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}">

      <label for="filter-category">{{ __('Category') }}</label>
      <select id="filter-category" name="category">
        <option value="">{{ __('All') }}</option>
        @foreach($cats as $c)
          <option value="{{ $c->id }}" @selected(request('category')==$c->id)>
            {{ $c->icon }} {{ $c->name }}
          </option>
        @endforeach
      </select>

      <label for="filter-brand">{{ __('Brand') }}</label>
      <select id="filter-brand" name="brand">
        <option value="">{{ __('All') }}</option>
        @foreach($brands as $b)
          <option value="{{ $b }}" @selected(request('brand')==$b)>{{ $b }}</option>
        @endforeach
      </select>

      <label>{{ __('Price range') }}</label>
      <div class="price-inputs">
        <input type="number" name="min" min="0" value="{{ request('min') }}" placeholder="{{ __('Min') }}" aria-label="{{ __('Min') }}">
        <span style="color:var(--mute)">—</span>
        <input type="number" name="max" min="0" value="{{ request('max') }}" placeholder="{{ __('Max') }}" aria-label="{{ __('Max') }}">
      </div>

      <label for="filter-sort">{{ __('Sort by') }}</label>
      <select id="filter-sort" name="sort">
        @foreach([
          'new' => 'Newest',
          'price_asc' => 'Price: low to high',
          'price_desc' => 'Price: high to low',
          'discount' => 'Biggest discount'
        ] as $k => $v)
          <option value="{{ $k }}" @selected(request('sort')==$k)>{{ __($v) }}</option>
        @endforeach
      </select>

      <button type="submit" class="btn" style="width:100%;margin-top:16px">{{ __('Apply') }}</button>
      <a class="btn ghost" style="width:100%;margin-top:8px" href="{{ url()->current() }}">{{ __('Reset') }}</a>
    </form>
  </aside>

  <!-- Products Catalog -->
  <section>
    @if($products->count())
      <div class="grid">
        @foreach($products as $p)
          @include('partials.card')
        @endforeach
      </div>
    @else
      <div class="card" style="text-align:center;padding:54px 24px">
        <div style="font-size:52px;margin-bottom:14px">🔍</div>
        <h3 style="margin-bottom:8px">{{ __('No products match your filters.') }}</h3>
        <p style="color:var(--mute);margin-bottom:20px;max-width:40ch;margin-inline:auto">
          {{ __('Smart watches, earbuds, mobile accessories and a dedicated perfume collection — all in one store.') }}
        </p>
        <a class="btn" href="{{ url()->current() }}">{{ __('Reset') }}</a>
      </div>
    @endif

    <div class="pager">
      {{ $products->links('pagination::simple-default') }}
    </div>
  </section>
</div>
@endsection