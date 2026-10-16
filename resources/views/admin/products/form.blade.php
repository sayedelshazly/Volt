@extends('admin.layout')
@section('title', $p->exists ? __('Edit product') : __('Add product'))
@section('content')

<div class="sec" style="margin-top:0">
  <div>
    <h2>{{ $p->exists ? '✏️ ' . __('Edit product') : '➕ ' . __('Add product') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ $p->exists ? $p->name : __('Create a new product in your store') }}</p>
  </div>
  <a href="/admin/products" class="btn ghost sm">← {{ __('Products') }}</a>
</div>

<form class="card" method="POST" enctype="multipart/form-data"
  action="{{ $p->exists ? '/admin/products/'.$p->id : '/admin/products' }}">
  @csrf
  @if($p->exists)
    @method('PUT')
  @endif

  <div class="form2">
    <div>
      <label for="name_ar">{{ __('Name (Arabic)') }} <span style="color:var(--err)">*</span></label>
      <input id="name_ar" name="name_ar" value="{{ old('name_ar', $p->name_ar) }}" required>
      @error('name_ar')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="name_en">{{ __('Name (English)') }} <span style="color:var(--err)">*</span></label>
      <input id="name_en" name="name_en" value="{{ old('name_en', $p->name_en) }}" required>
      @error('name_en')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="brand">{{ __('Brand') }}</label>
      <input id="brand" name="brand" value="{{ old('brand', $p->brand) }}" placeholder="e.g. Apple, Sony, Volt">
      @error('brand')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="category_id">{{ __('Category') }} <span style="color:var(--err)">*</span></label>
      <select id="category_id" name="category_id" required>
        @foreach($cats as $c)
          <option value="{{ $c->id }}" @selected(old('category_id', $p->category_id) == $c->id)>
            {{ $c->icon }} {{ $c->name }}
          </option>
        @endforeach
      </select>
      @error('category_id')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="price">{{ __('Price') }} ({{ config('volt.currency') }}) <span style="color:var(--err)">*</span></label>
      <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $p->price) }}" required>
      @error('price')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="old_price">{{ __('Old price') }} ({{ config('volt.currency') }})</label>
      <input id="old_price" type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price', $p->old_price) }}" placeholder="{{ __('Leave empty if no discount') }}">
      @error('old_price')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="stock">{{ __('Stock') }} <span style="color:var(--err)">*</span></label>
      <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $p->stock ?? 0) }}" required>
      @error('stock')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="image">{{ __('Image') }}</label>
      <input id="image" type="file" name="image" accept="image/*">
      @if($p->image_url)
        <div style="margin-top:8px;display:flex;align-items:center;gap:10px">
          <img src="{{ $p->image_url }}" alt="{{ $p->name }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
          <small style="color:var(--mute)">{{ __('Current image') }}</small>
        </div>
      @endif
      @error('image')<div class="err">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="form2" style="margin-top:14px">
    <div>
      <label for="desc_ar">{{ __('Description (Arabic)') }}</label>
      <textarea id="desc_ar" name="desc_ar" rows="3">{{ old('desc_ar', $p->desc_ar) }}</textarea>
      @error('desc_ar')<div class="err">{{ $message }}</div>@enderror
    </div>

    <div>
      <label for="desc_en">{{ __('Description (English)') }}</label>
      <textarea id="desc_en" name="desc_en" rows="3">{{ old('desc_en', $p->desc_en) }}</textarea>
      @error('desc_en')<div class="err">{{ $message }}</div>@enderror
    </div>
  </div>

  <div style="display:flex;gap:24px;margin:20px 0;flex-wrap:wrap">
    <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;margin:0">
      <input type="checkbox" name="is_featured" value="1" style="width:auto;cursor:pointer" @checked(old('is_featured', $p->is_featured))>
      <b>⭐ {{ __('Featured') }}</b>
    </label>

    <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;margin:0">
      <input type="checkbox" name="is_active" value="1" style="width:auto;cursor:pointer" @checked(old('is_active', $p->exists ? $p->is_active : true))>
      <b>👁️ {{ __('Active') }}</b>
    </label>
  </div>

  <div style="display:flex;gap:12px;margin-top:20px">
    <button type="submit" class="btn">
      💾 {{ __('Save') }}
    </button>
    <a class="btn ghost" href="/admin/products">
      {{ __('Cancel') }}
    </a>
  </div>
</form>

@endsection