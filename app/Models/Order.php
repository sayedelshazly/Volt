<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
    protected $guarded = [];
    public const FLOW = ['pending', 'processing', 'shipped', 'delivered'];
    public function items() { return $this->hasMany(OrderItem::class); }
}
