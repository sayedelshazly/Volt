@extends('admin.layout')
@section('title', __('Products'))
@section('content')

@php $cur = config('volt.currency'); @endphp

<div class="sec" style="margin-top:0">
  <div>
    <h2>📦 {{ __('Products') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Manage inventory, prices, and product visibility') }}</p>
  </div>
  <a class="btn" href="/admin/products/create">➕ {{ __('Add product') }}</a>
</div>

<!-- Search Bar -->
<form method="GET" style="margin-bottom:16px" class="search" style="max-width:100%;margin:0 0 16px">
  <input name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}" style="padding-inline-start:16px">
</form>

<div class="card tw" style="padding:0;overflow:hidden">
  <table>
    <thead>
      <tr>
        <th style="width:48px"></th>
        <th>{{ __('Name') }}</th>
        <th>{{ __('Category') }}</th>
        <th>{{ __('Price') }}</th>
        <th style="text-align:center">{{ __('Stock') }}</th>
        <th>{{ __('Status') }}</th>
        <th style="text-align:end">{{ __('Action') }}</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $p)
        <tr>
          <td style="text-align:center;font-size:24px">
            @if($p->image_url)
              <img src="{{ $p->image_url }}" alt="{{ $p->name }}" style="width:40px;height:40px;object-fit:cover;border-radius:8px">
            @else
              {{ $p->category->icon ?? '⚡' }}
            @endif
          </td>
          <td>
            <b>{{ $p->name }}</b>
            <br>
            <small style="color:var(--mute)">{{ $p->brand }}</small>
          </td>
          <td>
            <span class="pill" style="font-size:11px">{{ $p->category->name ?? '' }}</span>
          </td>
          <td>
            <b style="color:var(--blue)">{{ number_format($p->price) }} {{ $cur }}</b>
            @if($p->old_price)
              <br><small style="color:var(--mute);text-decoration:line-through">{{ number_format($p->old_price) }}</small>
            @endif
          </td>
          <td style="text-align:center">
            <span class="pill {{ $p->stock > 5 ? 'paid' : ($p->stock > 0 ? 'pending' : 'cancelled') }}">
              {{ $p->stock }}
            </span>
          </td>
          <td>
            <span class="pill {{ $p->is_active ? 'paid' : 'cancelled' }}">
              {{ $p->is_active ? __('Active') : __('Hidden') }}
            </span>
          </td>
          <td style="text-align:end;white-space:nowrap">
            <a class="btn sm ghost" href="/admin/products/{{ $p->id }}/edit" title="{{ __('Edit product') }}">✎</a>
            @if(auth()->user()->role === 'admin')
              <form method="POST" action="/admin/products/{{ $p->id }}" style="display:inline" onsubmit="return confirm('{{ __('Delete?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn sm danger" title="{{ __('Delete?') }}">🗑</button>
              </form>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:36px;color:var(--mute)">
            {{ __('No products match your filters.') }}
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="pager">
  {{ $products->links('pagination::simple-default') }}
</div>

@endsection