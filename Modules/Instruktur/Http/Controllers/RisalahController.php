<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Risalah;
use App\Models\Kursus;
use Illuminate\Http\Request;

class RisalahController extends Controller
{
    public function index(Kursus $kursus)
    {
        $risalahs = $kursus->risalahs()->latest()->get();
        return view('instruktur::instruktur.risalah.index', compact('kursus', 'risalahs'));
    }

    public function edit(Risalah $risalah)
    {
        // ensure the logged instruktur owns the kursus
        $instruktur = auth()->user()->instruktur;
        if (!$instruktur || $instruktur->id !== $risalah->instruktur_id) {
            abort(403);
        }

        return view('instruktur::instruktur.risalah.edit', compact('risalah'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        $instruktur = auth()->user()->instruktur;
        if (!$instruktur || $instruktur->id !== $risalah->instruktur_id) {
            abort(403);
        }

        $request->validate([
            'materi' => 'required|string',
            'catatan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:5120'
        ]);

        $data = $request->only(['materi','catatan']);
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $path = $file->store('public/risalah');
            $data['dokumen'] = $path;
        }
        $risalah->update($data);

        return redirect("/instruktur/kursus/{$risalah->kursus_id}/risalah")->with('success', 'Risalah diperbarui');
    }
    public function download(Risalah $risalah)
    {
        if (!$risalah->dokumen) {
            abort(404, 'Dokumen tidak ditemukan');
        }
        $kursusNama = $risalah->kursus ? $risalah->kursus->nama : 'Kursus';
        $pertemuanKe = $risalah->pertemuan_ke ?? '';
        $ext = pathinfo($risalah->dokumen, PATHINFO_EXTENSION);
        $filename = str_replace(' ', '_', $kursusNama) . '_Pertemuan_' . $pertemuanKe . ($ext ? ('.' . $ext) : '');
        return response()->download(storage_path('app/' . $risalah->dokumen), $filename);
    }
}
