<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\{Category, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    public function index(Request $r) {
        $q = Product::with('category')->latest();
        if ($s = $r->q) $q->where(fn ($w) => $w->where('name_en', 'like', "%$s%")->orWhere('name_ar', 'like', "%$s%"));
        return view('admin.products.index', ['products' => $q->paginate(15)->withQueryString()]);
    }
    public function create() { return view('admin.products.form', ['p' => new Product, 'cats' => Category::all()]); }
    public function store(ProductRequest $r) { Product::create($this->data($r)); return redirect()->route('admin.products.index')->with('ok', __('Saved')); }
    public function edit(Product $product) { return view('admin.products.form', ['p' => $product, 'cats' => Category::all()]); }
    public function update(ProductRequest $r, Product $product) {
        $d = $this->data($r);
        if (isset($d['image']) && $product->image) Storage::disk('public')->delete($product->image);
        $product->update($d);
        return redirect()->route('admin.products.index')->with('ok', __('Saved'));
    }
    public function destroy(Product $product) {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('ok', __('Deleted'));
    }
    private function data(ProductRequest $r): array {
        $d = $r->safe()->except('image');
        $d['is_featured'] = $r->boolean('is_featured'); $d['is_active'] = $r->boolean('is_active');
        if ($r->hasFile('image')) $d['image'] = $r->file('image')->store('products', 'public');
        return $d;
    }
}
