<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('talent')
            ->where('is_published', true)
            ->latest()
            ->paginate(10);

        $talents = User::where('role', 'talent')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('testimonials.index', compact('testimonials', 'talents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_alias' => ['required', 'string', 'max:100'],
            'talent_id' => ['nullable', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:2000'],
        ], [
            'customer_alias.required' => 'Nama atau inisial panggilan kamu wajib diisi.',
            'customer_alias.max' => 'Nama alias maksimal 100 karakter.',
            'talent_id.exists' => 'Talent yang dipilih tidak valid.',
            'rating.required' => 'Silakan pilih rating kepuasan bintang.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'content.required' => 'Tuliskan ulasan atau cerita pengalaman kamu.',
            'content.max' => 'Isi ulasan testimoni maksimal 2000 karakter.',
        ]);

        Testimonial::create([
            'customer_alias' => $validated['customer_alias'],
            'talent_id' => !empty($validated['talent_id']) ? $validated['talent_id'] : null,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'screenshot_path' => null,
            'is_published' => false, // Wajib disetujui admin terlebih dahulu
        ]);

        return redirect()->route('testimonials.index')
            ->with('success', 'Terima kasih atas ulasanmu! Testimoni kamu telah berhasil dikirim dan akan ditampilkan setelah ditinjau serta disetujui oleh admin.');
    }
}

