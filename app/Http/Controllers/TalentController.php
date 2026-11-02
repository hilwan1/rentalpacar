<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));
        $selectedCity = trim($request->query('city', ''));

        $query = User::where('role', 'talent')
            ->where('is_active', true)
            ->with(['services' => function ($q) {
                $q->where('is_active', true);
            }]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if (!empty($selectedCity)) {
            $query->where('city', $selectedCity);
        }

        $talents = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $availableCities = User::where('role', 'talent')
            ->where('is_active', true)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        return view('talents.index', compact('talents', 'search', 'selectedCity', 'availableCities'));
    }

    public function show($id)
    {
        $talent = User::where('role', 'talent')
            ->where('is_active', true)
            ->with(['services' => function ($query) {
                $query->where('is_active', true);
            }])
            ->findOrFail($id);

        return view('talents.show', compact('talent'));
    }
}

