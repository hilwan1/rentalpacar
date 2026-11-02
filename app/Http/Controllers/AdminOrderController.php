<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $search = trim($request->query('q', ''));
        $talentId = $request->query('talent_id', '');
        $dateFilter = $request->query('date', 'all');

        $query = Order::with(['talent', 'service']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_whatsapp', 'like', "%{$search}%")
                  ->orWhere('service_name_snapshot', 'like', "%{$search}%")
                  ->orWhereHas('talent', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($talentId)) {
            $query->where('talent_id', $talentId);
        }

        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        $orders = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $weekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $totalRevenue = (int) Order::sum('price_snapshot');

        $talents = User::where('role', 'talent')->orderBy('name')->get();

        return view('admin.orders.index', compact(
            'orders',
            'search',
            'talentId',
            'dateFilter',
            'totalOrders',
            'todayOrders',
            'weekOrders',
            'totalRevenue',
            'talents'
        ));
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $order = Order::with(['talent', 'service'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $order = Order::findOrFail($id);
        $orderCode = $order->order_code;
        $order->delete();

        return back()->with('success', "Pesanan {$orderCode} berhasil dihapus dari riwayat sistem.");
    }
}

