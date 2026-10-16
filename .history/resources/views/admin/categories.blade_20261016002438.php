@extends('admin.layout')
@section('title', __('Categories'))
@section('content')
<h2 style="margin-bottom:14px">{{ __('Categories') }}</h2>
<div class="two" style="grid-template-columns:1.3fr 1fr">
<div class="card tw"><table><tr><th></th><th>{{ __('Name') }}</th><th>{{ __('Section') }}</th><th>{{ __('Products') }}</th><th></th></tr>
@foreach($cats as $c)<tr><td>{{ $c->icon }}</td><td>{{ $c->name_ar }} / {{ $c->name_en }}</td><td>{{ $c->section==='perfume'?__('Perfumes'):__('Electronics') }}</td><td>{{ $c->products_count }}</td>
<td style="white-space:nowrap"><a class="btn sm ghost" href="/admin/categories/{{ $c->id }}/edit">✎</a>
<form method="POST" action="/admin/categories/{{ $c->id }}" style="display:inline" onsubmit="return confirm('{{ __('Delete?') }}')">@csrf @method('DELETE')<button class="btn sm danger">🗑</button></form></td></tr>@endforeach</table></div>
<form class="card" style="align-self:start" method="POST" action="{{ $edit ? '/admin/categories/'.$edit->id : '/admin/categories' }}">@csrf @if($edit) @method('PUT') @endif
<h4>{{ $edit ? __('Edit category') : __('Add category') }}</h4>
<label>{{ __('Name (Arabic)') }}</label><input name="name_ar" value="{{ old('name_ar',$edit->name_ar??'') }}" required>
<label>{{ __('Name (English)') }}</label><input name="name_en" value="{{ old('name_en',$edit->name_en??'') }}" required>
<label>{{ __('Icon (emoji)') }}</label><input name="icon" value="{{ old('icon',$edit->icon??'⚡') }}" maxlength="8">
<label>{{ __('Section') }}</label><select name="section"><option value="tech" @selected(($edit->section??'')==='tech')>{{ __('Electronics') }}</option><option value="perfume" @selected(($edit->section??'')==='perfume')>{{ __('Perfumes') }}</option></select>
<button class="btn" style="margin-top:14px">{{ __('Save') }}</button></form></div>
@endsection
