@extends('admin.layout')
@section('title', __('Dashboard'))
@section('content')
@php $cur=config('volt.currency'); @endphp
<h2 style="margin-bottom:16px">{{ __('Dashboard') }}</h2>
<div class="stats">
  <div class="card stat"><b>{{ number_format($revenue) }} {{ $cur }}</b><span>{{ __('Revenue') }}</span></div>
  <div class="card stat"><b>{{ $orders }}</b><span>{{ __('Orders') }} ({{ $pending }} {{ __('Pending') }})</span></div>
  <div class="card stat"><b>{{ $products }}</b><span>{{ __('Products') }}</span></div>
  <div class="card stat"><b>{{ $customers }}</b><span>{{ __('Customers') }}</span></div>
</div>
<div class="charts">
  <div class="card">
    <h4>{{ __('Last 7 days') }}</h4><canvas id="c1" height="120"></canvas>
  </div>
  <div class="card">
    <h4>{{ __('Orders by status') }}</h4><canvas id="c2"></canvas>
  </div>
</div>
<div class="three">
  <div class="card tw">
    <h4>🔥 {{ __('Top products') }}</h4>
    <table>@foreach($top as $t)<tr>
        <td>{{ $t->name }}</td>
        <td>×{{ $t->q }}</td>
        <td>{{ number_format($t->r) }}</td>
      </tr>@endforeach</table>
  </div>
  <div class="card tw">
    <h4>⚠️ {{ __('Low stock') }}</h4>
    <table>@forelse($low as $p)<tr>
        <td>{{ $p->name }}</td>
        <td><span class="pill {{ $p->stock?'':'cancelled' }}">{{ $p->stock }}</span></td>
      </tr>@empty<tr>
        <td>—</td>
      </tr>@endforelse</table>
  </div>
  <div class="card tw">
    <h4>🧾 {{ __('Recent orders') }}</h4>
    <table>@foreach($recent as $o)<tr>
        <td><a href="/admin/orders/{{ $o->id }}" dir="ltr">{{ $o->number }}</a></td>
        <td>{{ number_format($o->total) }}</td>
        <td><span class="pill {{ $o->status }}">{{ __(ucfirst($o->status)) }}</span></td>
      </tr>@endforeach</table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
  const css=getComputedStyle(document.documentElement),mute=css.getPropertyValue('--mute');
new Chart(c1,{type:'line',data:{labels:@json($labels),datasets:[{label:@json(__('Revenue')),data:@json($revSeries),borderColor:'#7c4dff',backgroundColor:'#7c4dff33',fill:true,tension:.4,yAxisID:'y'},{label:@json(__('Orders')),data:@json($ordSeries),borderColor:'#3b6cff',tension:.4,yAxisID:'y1'}]},options:{interaction:{mode:'index',intersect:false},scales:{y:{ticks:{color:mute}},y1:{position:'right',grid:{display:false},ticks:{color:mute,precision:0}},x:{ticks:{color:mute}}},plugins:{legend:{labels:{color:mute}}}}});
new Chart(c2,{type:'doughnut',data:{labels:@json($byStatus->keys()->map(fn($s)=>__(ucfirst($s)))),datasets:[{data:@json($byStatus->values()),backgroundColor:['#f5a524','#3b6cff','#7c4dff','#16a34a','#e5484d']}]},options:{plugins:{legend:{position:'bottom',labels:{color:mute}}}}});
</script>
@endsection