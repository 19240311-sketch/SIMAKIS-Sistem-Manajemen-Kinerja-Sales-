<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SourceController extends Controller
{
    public function index(): View
    {
        $sources = Source::orderBy('nama_sumber')->get();
        return view('master.sources.index', compact('sources'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:sumber_prospek,nama_sumber'],
        ]);

        Source::create([
            'nama_sumber' => $validated['name'],
            'status_aktif' => true,
        ]);

        return redirect()->route('master.sources.index')
            ->with('success', 'Sumber prospek baru berhasil ditambahkan!');
    }

    public function update(Request $request, Source $source): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:sumber_prospek,nama_sumber,' . $source->id],
            'is_active' => ['required', 'boolean'],
        ]);

        $source->update([
            'nama_sumber' => $validated['name'],
            'status_aktif' => (bool) $validated['is_active'],
        ]);

        return redirect()->route('master.sources.index')
            ->with('success', 'Data sumber prospek berhasil diperbarui!');
    }

    public function destroy(Source $source): RedirectResponse
    {
        if ($source->prospects()->exists()) {
            return redirect()->route('master.sources.index')
                ->with('error', 'Sumber prospek tidak dapat dihapus karena masih terhubung dengan data prospek.');
        }

        try {
            $source->delete();
            return redirect()->route('master.sources.index')
                ->with('success', 'Sumber prospek berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('master.sources.index')
                ->with('error', 'Gagal menghapus sumber prospek: ' . $e->getMessage());
        }
    }
}
