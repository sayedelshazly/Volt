<div class="pc" data-aos="fade-up">
  <a href="/product/{{ $p->id }}" class="pimg" aria-label="{{ $p->name }}">
    @if($p->image_url)
      <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
    @else
      <span style="font-size:56px;line-height:1">{{ $p->category->icon ?? '⚡' }}</span>
    @endif
    @if($p->discount)
      <span class="tag">-{{ $p->discount }}%</span>
    @endif
  </a>

  <div class="pb">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:6px">
      <small>{{ $p->brand }} · {{ $p->category->name ?? '' }}</small>
      @if($p->stock > 0)
        <span style="font-size:11px;color:var(--ok);font-weight:700;display:inline-flex;align-items:center;gap:3px">
          <span class="stock-dot"></span> {{ __('In stock') }}
        </span>
      @else
        <span style="font-size:11px;color:var(--err);font-weight:700">{{ __('Out of stock') }}</span>
      @endif
    </div>

    <h3>
      <a href="/product/{{ $p->id }}">{{ $p->name }}</a>
    </h3>

    <div class="price">
      <span>{{ number_format($p->price) }} {{ config('volt.currency') }}</span>
      @if($p->old_price)
        <s>{{ number_format($p->old_price) }}</s>
      @endif
    </div>

    @if($p->stock > 0)
      <form class="js-add" method="POST" action="/cart/{{ $p->id }}">
        @csrf
        <button type="submit" class="btn" style="width:100%">
          🛒 {{ __('Add to cart') }}
        </button>
      </form>
    @else
      <button class="btn ghost" disabled>{{ __('Out of stock') }}</button>
    @endif
  </div>
</div>