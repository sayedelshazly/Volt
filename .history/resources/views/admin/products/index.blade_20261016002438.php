@extends('admin.layout')
@section('title', __('Products'))
@section('content')
<div class="sec" style="margin-top:0"><h2>{{ __('Products') }}</h2><a class="btn" href="/admin/products/create">+ {{ __('Add product') }}</a></div>
<form method="GET" style="margin-bottom:12px"><input name="q" value="{{ request('q') }}" placeholder="{{ __('Search products...') }}"></form>
<div class="card tw"><table><tr><th></th><th>{{ __('Name') }}</th><th>{{ __('Category') }}</th><th>{{ __('Price') }}</th><th>{{ __('Stock') }}</th><th>{{ __('Status') }}</th><th></th></tr>
@foreach($products as $p)<tr><td>{{ $p->category->icon }}</td><td>{{ $p->name }}</td><td>{{ $p->category->name }}</td><td>{{ number_format($p->price) }}</td><td>{{ $p->stock }}</td>
<td><span class="pill {{ $p->is_active?'paid':'cancelled' }}">{{ $p->is_active?__('Active'):__('Hidden') }}</span></td>
<td style="white-space:nowrap"><a class="btn sm ghost" href="/admin/products/{{ $p->id }}/edit">✎</a>
@if(auth()->user()->role==='admin')<form method="POST" action="/admin/products/{{ $p->id }}" style="display:inline" onsubmit="return confirm('{{ __('Delete?') }}')">@csrf @method('DELETE')<button class="btn sm danger">🗑</button></form>@endif</td></tr>@endforeach</table></div>
<div class="pager">{{ $products->links('pagination::simple-default') }}</div>
@endsection
