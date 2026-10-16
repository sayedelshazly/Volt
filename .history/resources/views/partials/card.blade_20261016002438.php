<div class="pc" data-aos="fade-up">
  <a href="/product/{{ $p->id }}" class="pimg">
    @if($p->image_url)<img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">@else{{ $p->category->icon }}@endif
    @if($p->discount)<span class="tag">-{{ $p->discount }}%</span>@endif
  </a>
  <div class="pb">
    <small>{{ $p->brand }} · {{ $p->category->name }}</small>
    <h3><a href="/product/{{ $p->id }}">{{ $p->name }}</a></h3>
    <div class="price">{{ number_format($p->price) }} {{ config('volt.currency') }} @if($p->old_price)<s>{{ number_format($p->old_price) }}</s>@endif</div>
    @if($p->stock>0)
      <form class="js-add" method="POST" action="/cart/{{ $p->id }}">@csrf<button class="btn" style="width:100%">🛒 {{ __('Add to cart') }}</button></form>
    @else<button class="btn ghost" disabled>{{ __('Out of stock') }}</button>@endif
  </div>
</div>
