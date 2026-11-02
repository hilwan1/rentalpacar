<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $totalOrders = Order::count();
        $weeklyOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $totalTalents = User::where('role', 'talent')->count();
        $activeTalents = User::where('role', 'talent')->where('is_active', true)->count();
        $totalTestimonials = Testimonial::count();

        $recentOrders = Order::with('talent')->latest()->take(10)->get();
        $talents = User::where('role', 'talent')->withCount('orders')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'weeklyOrders',
            'totalTalents',
            'activeTalents',
            'totalTestimonials',
            'recentOrders',
            'talents'
        ));
    }
}

