<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTalentController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $search = trim($request->query('q', ''));
        $status = $request->query('status', 'all');

        $query = User::where('role', 'talent')
            ->withCount(['services', 'orders']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $talents = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $totalTalents = User::where('role', 'talent')->count();
        $activeCount = User::where('role', 'talent')->where('is_active', true)->count();
        $inactiveCount = User::where('role', 'talent')->where('is_active', false)->count();

        return view('admin.talents.index', compact(
            'talents',
            'search',
            'status',
            'totalTalents',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function toggleStatus(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $talent = User::where('role', 'talent')->findOrFail($id);
        $talent->is_active = !$talent->is_active;
        $talent->save();

        $statusText = $talent->is_active ? 'diaktifkan dan dapat tampil di katalog publik' : 'dinonaktifkan dari katalog publik';

        return back()->with('success', "Status akun talent {$talent->name} berhasil {$statusText}.");
    }
}

