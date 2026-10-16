<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    private function rules(): array {
        return ['name_ar' => 'required|string|max:80', 'name_en' => 'required|string|max:80',
                'icon' => 'nullable|string|max:8', 'section' => 'required|in:tech,perfume'];
    }
    public function index() { return view('admin.categories', ['cats' => Category::withCount('products')->get(), 'edit' => null]); }
    public function edit(Category $category) { return view('admin.categories', ['cats' => Category::withCount('products')->get(), 'edit' => $category]); }
    public function store(Request $r) { Category::create($r->validate($this->rules())); return redirect()->route('admin.categories.index')->with('ok', __('Saved')); }
    public function update(Request $r, Category $category) { $category->update($r->validate($this->rules())); return redirect()->route('admin.categories.index')->with('ok', __('Saved')); }
    public function destroy(Category $category) {
        if ($category->products()->exists()) return back()->withErrors(['cat' => __('Category has products')]);
        $category->delete(); return back()->with('ok', __('Deleted'));
    }
}
