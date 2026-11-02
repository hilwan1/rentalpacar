<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TopTalentController extends Controller
{
    public function index(Request $request)
    {
        $weekOffset = max(0, min(8, (int) $request->query('week', 0)));

        $targetDate = now()->subWeeks($weekOffset);
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        $talents = User::where('role', 'talent')
            ->where('is_active', true)
            ->withCount(['orders' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            }])
            ->with(['services' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderByDesc('orders_count')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalWeeklyOrders = $talents->sum('orders_count');

        $weekLabel = $weekOffset === 0
            ? 'Minggu Ini'
            : ($weekOffset === 1 ? '1 Minggu Lalu' : $weekOffset . ' Minggu Lalu');

        $dateRangeText = $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M Y');

        return view('talents.top', compact(
            'talents',
            'startOfWeek',
            'endOfWeek',
            'weekOffset',
            'weekLabel',
            'dateRangeText',
            'totalWeeklyOrders'
        ));
    }
}

