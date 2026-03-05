<?php

namespace App\Http\Controllers\Admin;

use App\Models\CertificateTemplate;
use App\Models\Kursus;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * List all templates.
     */
    public function index()
    {
        $templates = CertificateTemplate::with('kursus')->latest()->paginate(20);
        return view('admin.certificate-templates.index', compact('templates'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $kursus = Kursus::all();
        $defaultTemplate = CertificateTemplate::getDefault();

        return view('admin.certificate-templates.create', compact('kursus', 'defaultTemplate'));
    }

    /**
     * Store new template.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kursus_id' => 'nullable|exists:kursuses,id',
            'html_template' => 'required|string',
            'is_default' => 'boolean',
            'signature' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'kursus_id', 'html_template', 'is_default']);
        $data['is_default'] = $request->boolean('is_default');

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $path = $file->store('signatures', 'local');
            $data['signature_path'] = $path;
        }

        CertificateTemplate::create($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil dibuat.');
    }

    /**
     * Show edit form.
     */
    public function edit(CertificateTemplate $template)
    {
        $kursus = Kursus::all();
        return view('admin.certificate-templates.edit', compact('template', 'kursus'));
    }

    /**
     * Update template.
     */
    public function update(Request $request, CertificateTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kursus_id' => 'nullable|exists:kursuses,id',
            'html_template' => 'required|string',
            'is_default' => 'boolean',
            'signature' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'kursus_id', 'html_template', 'is_default']);
        $data['is_default'] = $request->boolean('is_default');

        if ($request->hasFile('signature')) {
            if ($template->signature_path) {
                Storage::disk('local')->delete($template->signature_path);
            }
            $file = $request->file('signature');
            $path = $file->store('signatures', 'local');
            $data['signature_path'] = $path;
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil diupdate.');
    }

    /**
     * Delete template.
     */
    public function destroy(CertificateTemplate $template)
    {
        if ($template->certificates()->exists()) {
            return back()->with('error', 'Template masih dipakai oleh sertifikat.');
        }

        if ($template->signature_path) {
            Storage::disk('local')->delete($template->signature_path);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }
}
