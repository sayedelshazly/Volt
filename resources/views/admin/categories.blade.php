@extends('admin.layout')
@section('title', __('Categories'))
@section('content')

<div class="sec" style="margin-top:0">
  <div>
    <h2>🗂️ {{ __('Categories') }}</h2>
    <p style="color:var(--mute);font-size:14px">{{ __('Manage store categories and product sections') }}</p>
  </div>
</div>

<div class="two">
  <!-- Categories List Table -->
  <div class="card tw" style="padding:0;overflow:hidden">
    <table>
      <thead>
        <tr>
          <th style="width:40px"></th>
          <th>{{ __('Name') }}</th>
          <th>{{ __('Section') }}</th>
          <th>{{ __('Products') }}</th>
          <th style="text-align:end">{{ __('Action') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cats as $c)
          <tr>
            <td style="font-size:22px;text-align:center">{{ $c->icon }}</td>
            <td>
              <b>{{ $c->name_ar }}</b>
              <br>
              <small style="color:var(--mute)">{{ $c->name_en }}</small>
            </td>
            <td>
              <span class="pill {{ $c->section === 'perfume' ? 'shipped' : 'paid' }}" style="font-size:11px">
                {{ $c->section === 'perfume' ? __('Perfumes') : __('Electronics') }}
              </span>
            </td>
            <td><b>{{ $c->products_count }}</b></td>
            <td style="text-align:end;white-space:nowrap">
              <a class="btn sm ghost" href="/admin/categories/{{ $c->id }}/edit" title="{{ __('Edit category') }}">✎</a>
              <form method="POST" action="/admin/categories/{{ $c->id }}" style="display:inline" onsubmit="return confirm('{{ __('Delete?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn sm danger" title="{{ __('Delete?') }}">🗑</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Add / Edit Category Form -->
  <form class="card" style="align-self:start" method="POST" action="{{ $edit ? '/admin/categories/'.$edit->id : '/admin/categories' }}">
    @csrf
    @if($edit)
      @method('PUT')
    @endif

    <h3 style="font-size:16px;font-weight:700;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--line)">
      {{ $edit ? '✏️ ' . __('Edit category') : '➕ ' . __('Add category') }}
    </h3>

    <div>
      <label for="cat_name_ar">{{ __('Name (Arabic)') }}</label>
      <input id="cat_name_ar" name="name_ar" value="{{ old('name_ar', $edit->name_ar ?? '') }}" required>
    </div>

    <div>
      <label for="cat_name_en">{{ __('Name (English)') }}</label>
      <input id="cat_name_en" name="name_en" value="{{ old('name_en', $edit->name_en ?? '') }}" required>
    </div>

    <div>
      <label for="cat_icon">{{ __('Icon (emoji)') }}</label>
      <input id="cat_icon" name="icon" value="{{ old('icon', $edit->icon ?? '⚡') }}" maxlength="8">
    </div>

    <div>
      <label for="cat_section">{{ __('Section') }}</label>
      <select id="cat_section" name="section">
        <option value="tech" @selected(($edit->section ?? '') === 'tech')>{{ __('Electronics') }}</option>
        <option value="perfume" @selected(($edit->section ?? '') === 'perfume')>{{ __('Perfumes') }}</option>
      </select>
    </div>

    <button type="submit" class="btn" style="width:100%;margin-top:16px">
      {{ __('Save') }}
    </button>
    @if($edit)
      <a class="btn ghost sm" style="width:100%;margin-top:8px" href="/admin/categories">{{ __('Cancel') }}</a>
    @endif
  </form>
</div>

@endsection