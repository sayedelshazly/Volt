<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
    public function index(Request $r) {
        $q = Order::latest();
        if ($r->status) $q->where('status', $r->status);
        if ($s = $r->q) $q->where(fn ($w) => $w->where('number', 'like', "%$s%")->orWhere('phone', 'like', "%$s%")->orWhere('name', 'like', "%$s%"));
        return view('admin.orders.index', ['orders' => $q->paginate(15)->withQueryString()]);
    }
    public function show(Order $order) { return view('admin.orders.show', ['o' => $order->load('items')]); }
    public function update(Request $r, Order $order) {
        $d = $r->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled',
                           'payment_status' => 'required|in:unpaid,paid,failed,refunded']);
        DB::transaction(function () use ($order, $d) {
            if ($d['status'] === 'cancelled' && $order->status !== 'cancelled')   // إرجاع المخزون عند الإلغاء
                foreach ($order->items as $i) if ($i->product_id) DB::table('products')->where('id', $i->product_id)->increment('stock', $i->qty);
            $order->update($d);
        });
        return back()->with('ok', __('Saved'));
    }
}
