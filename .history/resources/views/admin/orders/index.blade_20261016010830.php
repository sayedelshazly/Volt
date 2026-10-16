@extends('admin.layout')
@section('title', __('Orders'))
@section('content')
<h2 style="margin-bottom:14px">{{ __('Orders') }}</h2>
<form method="GET" class="form2" style="margin-bottom:12px"><input name="q" value="{{ request('q') }}"
        placeholder="{{ __('Order number, phone or name') }}">
    <select name="status" onchange="this.form.submit()">
        <option value="">{{ __('All') }}</option>@foreach(['pending','processing','shipped','delivered','cancelled'] as
        $s)<option value="{{ $s }}" @selected(request('status')==$s)>{{ __(ucfirst($s)) }}</option>@endforeach
    </select>
</form>
<div class="card tw">
    <table>
        <tr>
            <th>#</th>
            <th>{{ __('Customer') }}</th>
            <th>{{ __('Total') }}</th>
            <th>{{ __('Payment') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Date') }}</th>
        </tr>
        @foreach($orders as $o)<tr>
            <td><a dir="ltr" href="/admin/orders/{{ $o->id }}"><b>{{ $o->number }}</b></a></td>
            <td>{{ $o->name }}<br><small dir="ltr">{{ $o->phone }}</small></td>
            <td>{{ number_format($o->total) }}</td>
            <td><span class="pill {{ $o->payment_status }}">{{ __(ucfirst($o->payment_status)) }}</span></td>
            <td><span class="pill {{ $o->status }}">{{ __(ucfirst($o->status)) }}</span></td>
            <td>{{ $o->created_at->format('Y-m-d H:i') }}</td>
        </tr>@endforeach
    </table>
</div>
<div class="pager">{{ $orders->links('pagination::simple-default') }}</div>
@endsection