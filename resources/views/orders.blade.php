@extends('layouts.app')
@section('title', __('My orders'))
@section('content')
<div class="sec">
    <h2>{{ __('My orders') }}</h2>
</div>
<div class="card tw">
    <table>
        <tr>
            <th>#</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Total') }}</th>
            <th>{{ __('Status') }}</th>
            <th></th>
        </tr>
        @forelse($orders as $o)<tr>
            <td dir="ltr">{{ $o->number }}</td>
            <td>{{ $o->created_at->format('Y-m-d') }}</td>
            <td>{{ number_format($o->total) }}</td>
            <td><span class="pill {{ $o->status }}">{{ __(ucfirst($o->status)) }}</span></td>
            <td><a class="btn sm ghost" href="/track?number={{ $o->number }}&phone={{ $o->phone }}">{{ __('Track')
                    }}</a></td>
        </tr>@empty<tr>
            <td colspan="5">{{ __('No orders yet.') }}</td>
        </tr>@endforelse
    </table>
</div>
<div class="pager">{{ $orders->links('pagination::simple-default') }}</div>
@endsection