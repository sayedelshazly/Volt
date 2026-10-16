<?php
namespace App\Http\Controllers;
use App\Models\{Category, Product};
use Illuminate\Http\Request;

class ShopController extends Controller {
    public function home() {
        return view('home', [
            'featured' => Product::active()->with('category')->where('is_feSatured', true)->latest()->take(8)->get(),
            'perfumes' => Product::active()->whereHas('category', fn ($q) => $q->where('section', 'perfume'))->latest()->take(4)->get(),
            'cats' => Category::where('section', 'tech')->get(),
        ]);
    }
    public function index(Request $r, string $section = 'tech') {
        abort_unless(in_array($section, ['tech', 'perfume']), 404);
        $r->validate(['q' => 'nullable|string|max:80', 'category' => 'nullable|integer', 'min' => 'nullable|numeric|min:0',
                      'max' => 'nullable|numeric|min:0', 'sort' => 'nullable|in:new,price_asc,price_desc,discount', 'brand' => 'nullable|string|max:60']);
        $q = Product::active()->with('category')->whereHas('category', fn ($c) => $c->where('section', $section));
        if ($s = $r->q) $q->where(fn ($w) => $w->where('name_ar', 'like', "%$s%")->orWhere('name_en', 'like', "%$s%")->orWhere('brand', 'like', "%$s%"));
        if ($r->category) $q->where('category_id', $r->category);
        if ($r->brand) $q->where('brand', $r->brand);
        if ($r->filled('min')) $q->where('price', '>=', $r->min);
        if ($r->filled('max')) $q->where('price', '<=', $r->max);
        match ($r->sort) {
            'price_asc' => $q->orderBy('price'), 'price_desc' => $q->orderByDesc('price'),
            'discount' => $q->orderByRaw('(old_price - price) desc'), default => $q->latest(),
        };
        return view('shop', [
            'section' => $section,
            'products' => $q->paginate(12)->withQueryString(),
            'cats' => Category::where('section', $section)->get(),
            'brands' => Product::active()->whereHas('category', fn ($c) => $c->where('section', $section))->whereNotNull('brand')->distinct()->pluck('brand'),
        ]);
    }
    public function show(Product $product) {
        abort_unless($product->is_active, 404);
        return view('product', ['p' => $product->load('category'),
            'related' => Product::active()->where('category_id', $product->category_id)->whereKeyNot($product->id)->take(4)->get()]);
    }
}
