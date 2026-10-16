<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Product, User};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
    public function __invoke() {
        $paid = Order::where('status', '!=', 'cancelled');
        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
        $rev = Order::where('status', '!=', 'cancelled')->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) d, SUM(total) s, COUNT(*) c')->groupBy('d')->get()->keyBy('d');
        return view('admin.dashboard', [
            'revenue' => (clone $paid)->sum('total'), 'orders' => Order::count(), 'pending' => Order::where('status', 'pending')->count(),
            'products' => Product::count(), 'customers' => User::where('role', 'customer')->count(),
            'labels' => $days->map(fn ($d) => substr($d, 5))->all(),
            'revSeries' => $days->map(fn ($d) => (float) ($rev[$d]->s ?? 0))->all(),
            'ordSeries' => $days->map(fn ($d) => (int) ($rev[$d]->c ?? 0))->all(),
            'byStatus' => Order::select('status', DB::raw('count(*) c'))->groupBy('status')->pluck('c', 'status'),
            'top' => OrderItem::select('name', DB::raw('SUM(qty) q'), DB::raw('SUM(qty*price) r'))->groupBy('name')->orderByDesc('q')->take(5)->get(),
            'low' => Product::where('stock', '<=', 5)->orderBy('stock')->take(6)->get(),
            'recent' => Order::latest()->take(6)->get(),
        ]);
    }
}
