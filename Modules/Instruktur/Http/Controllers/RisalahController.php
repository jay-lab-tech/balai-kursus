<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\InstrukturKursusLevel;
use App\Models\Risalah;
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
        if (! $this->canManage($risalah)) {
            abort(403);
        }

        return view('instruktur::instruktur.risalah.edit', compact('risalah'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        if (! $this->canManage($risalah)) {
            abort(403);
        }

        $request->validate([
            'materi' => 'required|string',
            'catatan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only(['materi', 'catatan']);
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $path = $file->store('public/risalah');
            $data['dokumen'] = $path;
        }
        $risalah->update($data);

        return redirect("/instruktur/kursus/{$risalah->kursus_id}/risalah")->with('success', 'Risalah diperbarui');
    }

    private function canManage(Risalah $risalah): bool
    {
        $instruktur = auth()->user()->instruktur;

        return $instruktur
            && InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
                ->where('kursus_id', $risalah->kursus_id)
                ->exists();
    }

    public function download(Risalah $risalah)
    {
        if (! $risalah->dokumen) {
            abort(404, 'Dokumen tidak ditemukan');
        }
        $kursusNama = $risalah->kursus ? $risalah->kursus->nama : 'Kursus';
        $pertemuanKe = $risalah->pertemuan_ke ?? '';
        $ext = pathinfo($risalah->dokumen, PATHINFO_EXTENSION);
        $filename = str_replace(' ', '_', $kursusNama).'_Pertemuan_'.$pertemuanKe.($ext ? ('.'.$ext) : '');

        return response()->download(storage_path('app/'.$risalah->dokumen), $filename);
    }
}
