<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('talent')
            ->where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('testimonials.index', compact('testimonials'));
    }
}

