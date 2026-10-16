<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest {
    public function authorize(): bool { return $this->user()?->role !== null && in_array($this->user()->role, ['admin', 'editor']); }
    public function rules(): array {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name_ar' => ['required', 'string', 'max:150'], 'name_en' => ['required', 'string', 'max:150'],
            'desc_ar' => ['nullable', 'string', 'max:3000'], 'desc_en' => ['nullable', 'string', 'max:3000'],
            'brand' => ['nullable', 'string', 'max:60'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999'],
            'old_price' => ['nullable', 'numeric', 'gt:price'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['sometimes', 'boolean'], 'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
