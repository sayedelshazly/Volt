<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller {
    public static function items() {
        $c = session('cart', []);
        return Product::active()->whereIn('id', array_keys($c))->get()->map(function ($p) use ($c) {
            $q = min((int) $c[$p->id], $p->stock);
            return (object) ['p' => $p, 'qty' => $q, 'sub' => $p->price * $q];
        })->filter(fn ($i) => $i->qty > 0)->values();
    }
    public static function fee(float $sub): float {
        return $sub == 0 || $sub >= config('volt.free_shipping_over') ? 0 : config('volt.shipping');
    }
    public function index() { return view('cart', ['items' => self::items()]); }
    public function add(Request $r, Product $product) {
        $q = $r->validate(['qty' => 'nullable|integer|min:1|max:20'])['qty'] ?? 1;
        $cart = session('cart', []);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $q, $product->stock, 20);
        session(['cart' => $cart]);
        return $r->wantsJson() ? response()->json(['count' => array_sum($cart), 'msg' => __('Added to cart')]) : back()->with('ok', __('Added to cart'));
    }
    public function update(Request $r, Product $product) {
        $q = $r->validate(['qty' => 'required|integer|min:0|max:20'])['qty'];
        $cart = session('cart', []);
        $q > 0 ? $cart[$product->id] = min($q, $product->stock) : $cart = array_diff_key($cart, [$product->id => 1]);
        session(['cart' => $cart]);
        return back();
    }
    public function remove(Product $product) {
        session(['cart' => array_diff_key(session('cart', []), [$product->id => 1])]);
        return back();
    }
}
