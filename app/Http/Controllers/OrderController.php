<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function track(Request $r) {
        $order = null;
        if ($r->filled('number')) {
            $r->validate(['number' => 'required|string|max:20', 'phone' => 'required|string|max:20']);
            $order = Order::with('items')->where('number', strtoupper(trim($r->number)))
                ->where('phone', preg_replace('/[\s\-]/', '', $r->phone))->first();
            if (!$order) session()->flash('err', __('Order not found'));
        }
        return view('track', compact('order'));
    }
    public function mine() {
        return view('orders', ['orders' => Order::with('items')->where('user_id', auth()->id())->latest()->paginate(10)]);
    }
}
