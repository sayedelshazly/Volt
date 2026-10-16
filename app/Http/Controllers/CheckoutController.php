<?php
namespace App\Http\Controllers;
use App\Http\Requests\CheckoutRequest;
use App\Models\{Order, Product};
use App\Services\SandboxGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller {
    public function form() {
        $items = CartController::items();
        if ($items->isEmpty()) return redirect('/cart');
        return view('checkout', ['items' => $items]);
    }
    public function store(CheckoutRequest $r, SandboxGateway $gw) {
        $items = CartController::items();
        if ($items->isEmpty()) return redirect('/cart');
        $sub = $items->sum('sub');                      // السعر يُحسب دائمًا من الداتابيز وليس من العميل
        $fee = CartController::fee($sub); $total = $sub + $fee;
        $pay = ['ok' => true, 'txn' => null];
        if ($r->payment_method === 'card') {
            $pay = $gw->charge($r->card_number, $total);
            if (!$pay['ok']) throw ValidationException::withMessages(['card_number' => $pay['msg']]);
        }
        $order = DB::transaction(function () use ($r, $items, $sub, $fee, $total, $pay) {
            $rows = Product::lockForUpdate()->whereIn('id', $items->pluck('p.id'))->get()->keyBy('id');
            foreach ($items as $i) {
                if ($rows[$i->p->id]->stock < $i->qty)
                    throw ValidationException::withMessages(['cart' => $i->p->name . ': ' . __('Out of stock')]);
            }
            $o = Order::create([
                'number' => 'VLT-' . strtoupper(Str::random(8)), 'user_id' => auth()->id(),
                'name' => $r->name, 'phone' => $r->phone, 'city' => $r->city, 'address' => $r->address, 'notes' => $r->notes,
                'subtotal' => $sub, 'shipping' => $fee, 'total' => $total, 'payment_method' => $r->payment_method,
                'payment_status' => $pay['txn'] ? 'paid' : 'unpaid', 'transaction_id' => $pay['txn'],
            ]);
            foreach ($items as $i) {
                $o->items()->create(['product_id' => $i->p->id, 'name' => $i->p->name_en, 'price' => $i->p->price, 'qty' => $i->qty]);
                $rows[$i->p->id]->decrement('stock', $i->qty);
            }
            return $o;
        });
        session()->forget('cart');
        return redirect()->route('track', ['number' => $order->number, 'phone' => $order->phone])->with('ok', __('Order placed successfully'));
    }
}
