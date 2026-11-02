<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminTestimonialController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $search = trim($request->query('q', ''));
        $status = $request->query('status', 'all');
        $talentId = $request->query('talent_id', '');

        $query = Testimonial::with('talent');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_alias', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('talent', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        if (!empty($talentId)) {
            if ($talentId === 'none') {
                $query->whereNull('talent_id');
            } else {
                $query->where('talent_id', $talentId);
            }
        }

        $testimonials = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCount = Testimonial::count();
        $publishedCount = Testimonial::where('is_published', true)->count();
        $draftCount = Testimonial::where('is_published', false)->count();
        $avgRating = Testimonial::avg('rating') ? round(Testimonial::avg('rating'), 1) : 5.0;

        $talents = User::where('role', 'talent')->orderBy('name')->get();

        return view('admin.testimonials.index', compact(
            'testimonials',
            'search',
            'status',
            'talentId',
            'totalCount',
            'publishedCount',
            'draftCount',
            'avgRating',
            'talents'
        ));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $talents = User::where('role', 'talent')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.testimonials.create', compact('talents'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $validated = $request->validate([
            'customer_alias' => ['required', 'string', 'max:100'],
            'talent_id' => ['nullable', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:2000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'customer_alias.required' => 'Nama atau alias pelanggan wajib diisi.',
            'customer_alias.max' => 'Nama alias maksimal 100 karakter.',
            'talent_id.exists' => 'Talent yang dipilih tidak valid.',
            'rating.required' => 'Beri penilaian rating bintang.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'content.required' => 'Isi testimoni atau cerita ulasan wajib diisi.',
            'content.max' => 'Isi testimoni maksimal 2000 karakter.',
            'screenshot.image' => 'File bukti percakapan harus berupa gambar.',
            'screenshot.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, atau WEBP.',
            'screenshot.max' => 'Ukuran gambar screenshot maksimal 2 MB.',
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('testimonials', 'public');
        }

        $isPublished = $request->boolean('is_published', true);

        Testimonial::create([
            'customer_alias' => $validated['customer_alias'],
            'talent_id' => !empty($validated['talent_id']) ? $validated['talent_id'] : null,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'screenshot_path' => $screenshotPath,
            'is_published' => $isPublished,
        ]);

        $statusMsg = $isPublished
            ? 'Testimoni berhasil ditambahkan dan langsung tampil di halaman testimoni publik.'
            : 'Testimoni berhasil disimpan sebagai draft (tidak tampil di publik).';

        return redirect()->route('admin.testimonials.index')->with('success', $statusMsg);
    }

    public function edit($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $testimonial = Testimonial::with('talent')->findOrFail($id);
        $talents = User::where('role', 'talent')
            ->orderBy('name')
            ->get();

        return view('admin.testimonials.edit', compact('testimonial', 'talents'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'customer_alias' => ['required', 'string', 'max:100'],
            'talent_id' => ['nullable', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:2000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_screenshot' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'customer_alias.required' => 'Nama atau alias pelanggan wajib diisi.',
            'customer_alias.max' => 'Nama alias maksimal 100 karakter.',
            'talent_id.exists' => 'Talent yang dipilih tidak valid.',
            'rating.required' => 'Beri penilaian rating bintang.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'content.required' => 'Isi testimoni atau cerita ulasan wajib diisi.',
            'content.max' => 'Isi testimoni maksimal 2000 karakter.',
            'screenshot.image' => 'File bukti percakapan harus berupa gambar.',
            'screenshot.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, atau WEBP.',
            'screenshot.max' => 'Ukuran gambar screenshot maksimal 2 MB.',
        ]);

        // Cek jika screenshot dihapus
        if ($request->boolean('remove_screenshot') && $testimonial->screenshot_path) {
            Storage::disk('public')->delete($testimonial->screenshot_path);
            $testimonial->screenshot_path = null;
        }

        // Cek jika upload screenshot baru
        if ($request->hasFile('screenshot')) {
            if ($testimonial->screenshot_path) {
                Storage::disk('public')->delete($testimonial->screenshot_path);
            }
            $testimonial->screenshot_path = $request->file('screenshot')->store('testimonials', 'public');
        }

        $testimonial->customer_alias = $validated['customer_alias'];
        $testimonial->talent_id = !empty($validated['talent_id']) ? $validated['talent_id'] : null;
        $testimonial->rating = $validated['rating'];
        $testimonial->content = $validated['content'];
        $testimonial->is_published = $request->boolean('is_published');
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Data testimoni berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->is_published = !$testimonial->is_published;
        $testimonial->save();

        $statusText = $testimonial->is_published ? 'diterbitkan dan tampil di publik' : 'diarsipkan sebagai draft (disembunyikan dari publik)';

        return back()->with('success', "Status testimoni untuk {$testimonial->customer_alias} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->screenshot_path) {
            Storage::disk('public')->delete($testimonial->screenshot_path);
        }

        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
