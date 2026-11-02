<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function index()
    {
        $talents = User::where('role', 'talent')
            ->where('is_active', true)
            ->with(['services' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return view('talents.index', compact('talents'));
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

