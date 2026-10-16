<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller {
    public function loginForm() { return view('auth.login'); }
    public function login(Request $r) {
        $c = $r->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (!Auth::attempt($c, $r->boolean('remember'))) {
            return back()->withErrors(['email' => __('Invalid credentials')])->onlyInput('email');
        }
        $r->session()->regenerate();   // حماية من Session Fixation
        return redirect()->intended(in_array(Auth::user()->role, ['admin', 'editor']) ? '/admin' : '/');
    }
    public function registerForm() { return view('auth.register'); }
    public function register(Request $r) {
        $d = $r->validate([
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'email' => ['required', 'email:rfc', 'max:120', 'unique:users'],
            'phone' => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);
        $user = User::create($d);       // role = customer افتراضيًا
        Auth::login($user);
        $r->session()->regenerate();
        return redirect('/');
    }
    public function logout(Request $r) {
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
        return redirect('/');
    }
}
