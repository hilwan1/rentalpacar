@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between py-4">
        <!-- Tampilan Mobile Ringkas (< sm) -->
        <div class="flex flex-1 items-center justify-between sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-rose-50 hover:text-rose-600 active:scale-95 transition">
                    &larr; Sebelumnya
                </a>
            @endif

            <span class="text-xs font-medium text-slate-600 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl">
                Hal. <strong class="text-slate-900">{{ $paginator->currentPage() }}</strong> dari {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-rose-50 hover:text-rose-600 active:scale-95 transition">
                    Berikutnya &rarr;
                </a>
            @else
                <span class="relative inline-flex items-center px-3 py-2 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                    Berikutnya &rarr;
                </span>
            @endif
        </div>

        <!-- Tampilan Tablet & Layar Lebar (>= sm) -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $paginator->firstItem() }}</span> sampai <span class="font-bold text-slate-800">{{ $paginator->lastItem() }}</span> dari <span class="font-bold text-slate-800">{{ $paginator->total() }}</span> data
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5">
                    {{-- Tombol Sebelumnya --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Sebelumnya" class="px-3 py-2 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                            &larr;
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 active:scale-95 transition">
                            &larr;
                        </a>
                    @endif

                    {{-- Elemen Nomor Halaman --}}
                    @foreach ($elements as $element)
                        {{-- Tanda Titik-Titik (...) --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="px-2 py-2 text-xs font-semibold text-slate-400 select-none">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Deretan Nomor Halaman --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="px-3.5 py-2 text-xs font-bold text-white bg-rose-600 rounded-xl shadow-xs border border-rose-600">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 active:scale-95 transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Berikutnya --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 active:scale-95 transition">
                            &rarr;
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Berikutnya" class="px-3 py-2 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                            &rarr;
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif

