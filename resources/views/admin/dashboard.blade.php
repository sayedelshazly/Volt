@extends('admin.layout')
@section('title', __('Dashboard'))
@section('content')

@php $cur = config('volt.currency'); @endphp

<div class="sec" style="margin-top:0">
  <div>
    <h2>📊 {{ __('Dashboard') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Store metrics and sales overview') }}</p>
  </div>
</div>

<!-- Key Stat Cards -->
<div class="stats">
  <div class="card stat">
    <div style="display:flex;justify-content:space-between;align-items:flex-start">
      <span>{{ __('Revenue') }}</span>
      <span style="font-size:20px">💰</span>
    </div>
    <b>{{ number_format($revenue) }} {{ $cur }}</b>
  </div>

  <div class="card stat">
    <div style="display:flex;justify-content:space-between;align-items:flex-start">
      <span>{{ __('Orders') }}</span>
      <span style="font-size:20px">🧾</span>
    </div>
    <b>{{ $orders }}</b>
    <small style="color:var(--mute)">{{ $pending }} {{ __('Pending') }}</small>
  </div>

  <div class="card stat">
    <div style="display:flex;justify-content:space-between;align-items:flex-start">
      <span>{{ __('Products') }}</span>
      <span style="font-size:20px">📦</span>
    </div>
    <b>{{ $products }}</b>
  </div>

  <div class="card stat">
    <div style="display:flex;justify-content:space-between;align-items:flex-start">
      <span>{{ __('Customers') }}</span>
      <span style="font-size:20px">👥</span>
    </div>
    <b>{{ $customers }}</b>
  </div>
</div>

<!-- Charts Section -->
<div class="charts">
  <div class="card" style="display:flex;flex-direction:column">
    <h4 style="margin-bottom:16px;font-weight:700">📈 {{ __('Last 7 days') }}</h4>
    <div style="position:relative;height:260px;width:100%">
      <canvas id="c1"></canvas>
    </div>
  </div>

  <div class="card" style="display:flex;flex-direction:column">
    <h4 style="margin-bottom:16px;font-weight:700">🍩 {{ __('Orders by status') }}</h4>
    <div style="position:relative;height:260px;width:100%">
      <canvas id="c2"></canvas>
    </div>
  </div>
</div>

<!-- Three Column Summary Lists -->
<div class="three">
  <div class="card tw" style="padding:18px">
    <h4 style="margin-bottom:14px;font-weight:700">🔥 {{ __('Top products') }}</h4>
    <table>
      <thead>
        <tr>
          <th>{{ __('Product') }}</th>
          <th>{{ __('Qty') }}</th>
          <th style="text-align:end">{{ __('Revenue') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($top as $t)
          <tr>
            <td><b>{{ $t->name }}</b></td>
            <td>×{{ $t->q }}</td>
            <td style="text-align:end;font-weight:700;color:var(--blue)">{{ number_format($t->r) }}</td>
          </tr>
        @empty
          <tr><td colspan="3" style="text-align:center;color:var(--mute)">—</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card tw" style="padding:18px">
    <h4 style="margin-bottom:14px;font-weight:700">⚠️ {{ __('Low stock') }}</h4>
    <table>
      <thead>
        <tr>
          <th>{{ __('Product') }}</th>
          <th style="text-align:end">{{ __('Stock') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($low as $p)
          <tr>
            <td>{{ $p->name }}</td>
            <td style="text-align:end">
              <span class="pill {{ $p->stock ? 'warn' : 'cancelled' }}">
                {{ $p->stock }}
              </span>
            </td>
          </tr>
        @empty
          <tr><td colspan="2" style="text-align:center;color:var(--mute)">—</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card tw" style="padding:18px">
    <h4 style="margin-bottom:14px;font-weight:700">🧾 {{ __('Recent orders') }}</h4>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>{{ __('Total') }}</th>
          <th style="text-align:end">{{ __('Status') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recent as $o)
          <tr>
            <td>
              <a href="/admin/orders/{{ $o->id }}" dir="ltr" style="font-weight:700;color:var(--blue)">
                {{ $o->number }}
              </a>
            </td>
            <td><b>{{ number_format($o->total) }}</b></td>
            <td style="text-align:end">
              <span class="pill {{ $o->status }}" style="font-size:11px">
                {{ __(ucfirst($o->status)) }}
              </span>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" style="text-align:center;color:var(--mute)">—</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const css = getComputedStyle(document.documentElement);
    const mute = css.getPropertyValue('--mute').trim() || '#64748b';
    const blue = css.getPropertyValue('--blue').trim() || '#2563eb';
    const violet = css.getPropertyValue('--violet').trim() || '#7c3aed';

    const c1El = document.getElementById('c1');
    if (c1El) {
      new Chart(c1El, {
        type: 'line',
        data: {
          labels: @json($labels),
          datasets: [
            {
              label: @json(__('Revenue')),
              data: @json($revSeries),
              borderColor: violet,
              backgroundColor: violet + '22',
              fill: true,
              tension: 0.4,
              yAxisID: 'y'
            },
            {
              label: @json(__('Orders')),
              data: @json($ordSeries),
              borderColor: blue,
              tension: 0.4,
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          scales: {
            y: { ticks: { color: mute }, grid: { color: css.getPropertyValue('--line').trim() || '#e2e8f0' } },
            y1: { position: 'right', grid: { display: false }, ticks: { color: mute, precision: 0 } },
            x: { ticks: { color: mute }, grid: { display: false } }
          },
          plugins: { legend: { labels: { color: mute } } }
        }
      });
    }

    const c2El = document.getElementById('c2');
    if (c2El) {
      new Chart(c2El, {
        type: 'doughnut',
        data: {
          labels: @json($byStatus->keys()->map(fn($s) => __(ucfirst($s)))),
          datasets: [{
            data: @json($byStatus->values()),
            backgroundColor: ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'],
            borderWidth: 2,
            borderColor: css.getPropertyValue('--surface').trim() || '#ffffff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'bottom', labels: { color: mute } } }
        }
      });
    }
  });
</script>

@endsection