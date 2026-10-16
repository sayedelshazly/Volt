@extends('admin.layout')
@section('title', __('Product'))
@section('content')
<h2 style="margin-bottom:14px">{{ $p->exists ? __('Edit product') : __('Add product') }}</h2>
<form class="card" method="POST" enctype="multipart/form-data" action="{{ $p->exists ? '/admin/products/'.$p->id : '/admin/products' }}">@csrf @if($p->exists) @method('PUT') @endif
<div class="form2">
@foreach([['name_ar','Name (Arabic)'],['name_en','Name (English)'],['brand','Brand'],['price','Price'],['old_price','Old price'],['stock','Stock']] as [$n,$l])
<div><label>{{ __($l) }}</label><input name="{{ $n }}" value="{{ old($n,$p->$n) }}" @if(in_array($n,['price','old_price','stock'])) type="number" step="0.01" min="0" @endif>@error($n)<div class="err">{{ $message }}</div>@enderror</div>@endforeach
<div><label>{{ __('Category') }}</label><select name="category_id">@foreach($cats as $c)<option value="{{ $c->id }}" @selected(old('category_id',$p->category_id)==$c->id)>{{ $c->icon }} {{ $c->name }}</option>@endforeach</select></div>
<div><label>{{ __('Image') }}</label><input type="file" name="image" accept="image/*">@error('image')<div class="err">{{ $message }}</div>@enderror</div>
<div><label>{{ __('Description (Arabic)') }}</label><textarea name="desc_ar" rows="3">{{ old('desc_ar',$p->desc_ar) }}</textarea></div>
<div><label>{{ __('Description (English)') }}</label><textarea name="desc_en" rows="3">{{ old('desc_en',$p->desc_en) }}</textarea></div>
</div>
<label style="font-weight:500"><input type="checkbox" name="is_featured" value="1" style="width:auto" @checked(old('is_featured',$p->is_featured))> {{ __('Featured') }}</label>
<label style="font-weight:500"><input type="checkbox" name="is_active" value="1" style="width:auto" @checked(old('is_active',$p->exists?$p->is_active:true))> {{ __('Active') }}</label>
<button class="btn" style="margin-top:14px">{{ __('Save') }}</button> <a class="btn ghost" href="/admin/products">{{ __('Cancel') }}</a></form>
@endsection
