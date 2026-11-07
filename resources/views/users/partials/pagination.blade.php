@if($users->total() > 0)
    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        {{-- Info Counter --}}
        <div class="text-slate-500 font-medium">
            Menampilkan <span class="font-bold text-slate-800">{{ $users->firstItem() ?? 0 }}</span>–<span class="font-bold text-slate-800">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-900">{{ $users->total() }}</span> akun
        </div>

        {{-- Page Buttons --}}
        @if($users->lastPage() > 1)
            <div class="flex items-center gap-1 flex-wrap">
                {{-- Previous Button --}}
                @if($users->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed font-medium text-xs border border-slate-200">
                        &larr; Sebelumnya
                    </span>
                @else
                    <button type="button"
                            onclick="goToUserPage({{ $users->currentPage() - 1 }})"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors shadow-2xs">
                        &larr; Sebelumnya
                    </button>
                @endif

                {{-- Numbered Page Links --}}
                @php
                    $currentPage = $users->currentPage();
                    $lastPage = $users->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @if($start > 1)
                    <button type="button" onclick="goToUserPage(1)"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors">
                        1
                    </button>
                    @if($start > 2)
                        <span class="px-1 text-slate-400">...</span>
                    @endif
                @endif

                @for($p = $start; $p <= $end; $p++)
                    @if($p == $currentPage)
                        <span class="px-3 py-1.5 rounded-lg bg-[#0A4DF3] text-white font-bold text-xs shadow-xs border border-blue-600">
                            {{ $p }}
                        </span>
                    @else
                        <button type="button" onclick="goToUserPage({{ $p }})"
                                class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors">
                            {{ $p }}
                        </button>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span class="px-1 text-slate-400">...</span>
                    @endif
                    <button type="button" onclick="goToUserPage({{ $lastPage }})"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors">
                        {{ $lastPage }}
                    </button>
                @endif

                {{-- Next Button --}}
                @if($users->hasMorePages())
                    <button type="button"
                            onclick="goToUserPage({{ $users->currentPage() + 1 }})"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors shadow-2xs">
                        Berikutnya &rarr;
                    </button>
                @else
                    <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed font-medium text-xs border border-slate-200">
                        Berikutnya &rarr;
                    </span>
                @endif
            </div>
        @endif
    </div>
@endif

