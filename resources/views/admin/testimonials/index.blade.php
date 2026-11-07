<x-admin-layout title="Kelola Testimoni - RentalPacar Admin" header="Kelola Testimoni">
    <div class="space-y-6">
        <!-- Top Header & Ringkasan -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Testimoni Pelanggan</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Testimoni Pelanggan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Tambah testimoni baru dari pelanggan, unggah bukti screenshot percakapan, dan atur apakah langsung tayang di publik.
                </p>

                <!-- Metrik Ringkas -->
                <div class="flex flex-wrap items-center gap-2 mt-4">
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                        Total: <strong class="text-slate-900">{{ $totalCount }}</strong>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                        Tayang Publik: <strong>{{ $publishedCount }}</strong>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold">
                        Menunggu Review / Draft: <strong>{{ $draftCount }}</strong>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold flex items-center gap-1">
                        <span>Rata-rata:</span>
                        <strong class="text-rose-900">{{ $avgRating }}</strong>
                        <svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Tombol Tambah Testimoni Baru -->
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('testimonials.index') }}" target="_blank"
                   class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 text-xs font-bold rounded-xl transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Lihat di Publik</span>
                </a>

                <a href="{{ route('admin.testimonials.create') }}"
                   class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/25 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Testimoni</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Pencarian Testimoni -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.testimonials.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Kolom Search -->
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="q"
                           value="{{ $search }}"
                           placeholder="Cari nama pelanggan, isi testimoni, atau nama talent..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white transition">
                </div>

                <!-- Dropdown Status -->
                <div class="w-full sm:w-44 shrink-0">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-700 transition cursor-pointer">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Hanya Disetujui (Publik)</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Menunggu Review / Draft</option>
                    </select>
                </div>

                <!-- Dropdown Filter Talent -->
                <div class="w-full sm:w-48 shrink-0">
                    <select name="talent_id"
                            onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-700 transition cursor-pointer">
                        <option value="" {{ $talentId === '' ? 'selected' : '' }}>Semua Talent</option>
                        <option value="none" {{ $talentId === 'none' ? 'selected' : '' }}>Tanpa Talent (Umum)</option>
                        @foreach ($talents as $t)
                            <option value="{{ $t->id }}" {{ (string)$talentId === (string)$t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Submit & Reset -->
                <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-xs transition shrink-0">
                    Cari
                </button>

                @if (!empty($search) || $status !== 'all' || !empty($talentId))
                    <a href="{{ route('admin.testimonials.index') }}"
                       class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition text-center shrink-0">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Testimoni -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($testimonials->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/70 text-slate-400 border-b border-slate-200/80">
                                <th class="py-3.5 px-5 font-semibold">Pelanggan</th>
                                <th class="py-3.5 px-4 font-semibold">Talent Bersangkutan</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Rating</th>
                                <th class="py-3.5 px-5 font-semibold">Isi Ulasan & Bukti</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Status Publik</th>
                                <th class="py-3.5 px-5 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($testimonials as $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <!-- Pelanggan & Tanggal -->
                                    <td class="py-4 px-5">
                                        <div class="font-bold text-slate-900">{{ $item->customer_alias }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</div>
                                    </td>

                                    <!-- Talent Terkait -->
                                    <td class="py-4 px-4">
                                        @if ($item->talent)
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 font-bold flex items-center justify-center overflow-hidden shrink-0 text-[10px]">
                                                    @if ($item->talent->avatar_url)
                                                        <img src="{{ $item->talent->avatar_url }}" alt="{{ $item->talent->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($item->talent->name, 0, 2)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-slate-800 block">{{ $item->talent->name }}</span>
                                                    <span class="text-[10px] text-slate-400 block">{{ $item->talent->city ?? 'Indonesia' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600">
                                                Layanan Umum
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Rating -->
                                    <td class="py-4 px-4 text-center">
                                        <div class="inline-flex items-center gap-0.5 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $item->rating ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 24 24">
                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                </svg>
                                            @endfor
                                            <span class="text-[11px] font-bold text-amber-700 ml-1">{{ $item->rating }}</span>
                                        </div>
                                    </td>

                                    <!-- Isi Ulasan & Bukti Screenshot -->
                                    <td class="py-4 px-5 max-w-xs">
                                        <p class="text-slate-700 line-clamp-2 leading-relaxed italic">
                                            "{{ $item->content }}"
                                        </p>
                                        @if ($item->screenshot_url)
                                            <div class="mt-1.5 flex items-center gap-1.5">
                                                <a href="{{ $item->screenshot_url }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 hover:text-rose-700 hover:underline">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>Lihat Bukti Foto</span>
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status Publikasi -->
                                    <td class="py-4 px-4 text-center">
                                        @if ($item->is_published)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Disetujui</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200" title="Menunggu persetujuan admin">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                <span>Menunggu Review</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-5 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Toggle Status / Setujui -->
                                            <form action="{{ route('admin.testimonials.toggle-status', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        title="{{ $item->is_published ? 'Sembunyikan / Ubah jadi draft' : 'Setujui & Terbitkan ke publik' }}"
                                                        class="p-1.5 rounded-lg border text-xs font-semibold transition {{ $item->is_published ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                    @if ($item->is_published)
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.testimonials.edit', $item->id) }}"
                                               title="Edit testimoni"
                                               class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- Hapus -->
                                            <form action="{{ route('admin.testimonials.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni dari {{ $item->customer_alias }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="Hapus testimoni"
                                                        class="p-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($testimonials->hasPages())
                    <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50">
                        {{ $testimonials->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="p-10 sm:p-14 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Testimoni</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-5">
                        @if (!empty($search) || $status !== 'all' || !empty($talentId))
                            Tidak ada testimoni yang cocok dengan kriteria pencarian dan filter saat ini.
                        @else
                            Mulai tambahkan ulasan pelanggan yang masuk agar dapat tampil di halaman testimoni publik.
                        @endif
                    </p>
                    <a href="{{ route('admin.testimonials.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Testimoni Pertama</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

