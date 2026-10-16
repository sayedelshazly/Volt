<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model {
    protected $guarded = [];
    public function products() { return $this->hasMany(Product::class); }
    public function getNameAttribute() { return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en; }
}
