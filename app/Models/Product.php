<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $guarded = [];
    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean'];
    public function category() { return $this->belongsTo(Category::class); }
    public function getNameAttribute() { return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en; }
    public function getDescAttribute() { return app()->getLocale() === 'ar' ? $this->desc_ar : $this->desc_en; }
    public function getImageUrlAttribute() { return $this->image ? asset('storage/' . $this->image) : null; }
    public function getDiscountAttribute() { return $this->old_price > $this->price ? round((1 - $this->price / $this->old_price) * 100) : 0; }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
