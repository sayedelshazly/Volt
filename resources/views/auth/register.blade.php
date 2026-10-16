@extends('layouts.app')
@section('title', __('Register'))
@section('content')
<form class="card auth" method="POST" action="/register">@csrf<h2>{{ __('Register') }}</h2>
@foreach([['name','Full name','text'],['email','Email','email'],['phone','Phone','tel'],['password','Password','password'],['password_confirmation','Confirm password','password']] as [$n,$l,$t])
<label>{{ __($l) }}</label><input type="{{ $t }}" name="{{ $n }}" value="{{ $t==='password'?'':old($n) }}" required>@error($n)<div class="err">{{ $message }}</div>@enderror @endforeach
<button class="btn" style="width:100%;margin-top:14px">{{ __('Register') }}</button></form>
@endsection
