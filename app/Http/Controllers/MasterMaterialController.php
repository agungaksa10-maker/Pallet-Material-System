<?php

namespace App\Http\Controllers;

use App\Models\MasterMaterial;
use App\Services\MaterialImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MasterMaterialController extends Controller
{
    /**
     * Display a listing of master materials.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = MasterMaterial::query();

        if (! empty($search)) {
            $query->search($search);
        }

        $materials = $query->orderBy('name', 'asc')
            ->paginate(20)
            ->withQueryString();

        $totalMaterials = MasterMaterial::count();
        $totalActive = MasterMaterial::active()->count();
        $totalInactive = MasterMaterial::where('is_active', false)->count();

        return view('master_materials.index', [
            'materials' => $materials,
            'totalMaterials' => $totalMaterials,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'currentSearch' => $search,
        ]);
    }

    /**
     * Store a newly created master material.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'item_code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'default_unit' => ['required', 'string', 'max:20'],
            'specification' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        $material = MasterMaterial::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil ditambahkan ke Master Data.',
                'material' => $material,
            ], 201);
        }

        return redirect()->route('master-materials.index')
            ->with('success', "Material \"{$material->name}\" berhasil ditambahkan ke Master Data.");
    }

    /**
     * Update the specified master material.
     */
    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $material = MasterMaterial::findOrFail($id);

        $validated = $request->validate([
            'item_code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'default_unit' => ['required', 'string', 'max:20'],
            'specification' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        $material->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data material berhasil diperbarui.',
                'material' => $material,
            ]);
        }

        return redirect()->route('master-materials.index')
            ->with('success', "Material \"{$material->name}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified master material.
     */
    public function destroy(int $id): RedirectResponse|JsonResponse
    {
        $material = MasterMaterial::findOrFail($id);
        $name = $material->name;
        $material->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Material \"{$name}\" berhasil dihapus.",
            ]);
        }

        return redirect()->route('master-materials.index')
            ->with('success', "Material \"{$name}\" berhasil dihapus dari Master Data.");
    }

    /**
     * Search endpoint for JSON autocomplete in pallet create/edit.
     */
    public function search(Request $request): JsonResponse
    {
        $query = MasterMaterial::query()->active();

        if ($request->filled('q')) {
            $query->search($request->query('q'));
        }

        $items = $query->orderBy('name', 'asc')
            ->limit(30)
            ->get(['id', 'item_code', 'name', 'default_unit', 'specification']);

        return response()->json($items);
    }

    /**
     * Import materials from uploaded Excel/CSV file.
     */
    public function import(Request $request, MaterialImportService $importService): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'update_existing' => ['nullable'],
        ], [
            'file.required' => 'Silakan pilih file Excel (.xlsx) atau CSV (.csv) untuk diimpor.',
            'file.file' => 'File yang diunggah tidak valid.',
            'file.max' => 'Ukuran file maksimal adalah 10 MB.',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['csv', 'txt', 'xlsx', 'xls'], true)) {
            return redirect()->route('master-materials.index')
                ->with('error', 'Format file tidak didukung. Harap unggah file dengan ekstensi .xlsx, .xls, atau .csv.');
        }

        $rows = $importService->parseFile($file);
        $updateExisting = $request->boolean('update_existing', true);
        $result = $importService->import($rows, $updateExisting);

        if (! $result['success']) {
            $errorMessage = implode(' ', $result['errors']);

            return redirect()->route('master-materials.index')
                ->with('error', 'Gagal mengimpor file: '.$errorMessage);
        }

        $msg = "Berhasil mengimpor {$result['imported']} material baru";
        if ($result['updated'] > 0) {
            $msg .= ", memperbarui {$result['updated']} data eksisting";
        }
        if ($result['skipped'] > 0) {
            $msg .= ", dan {$result['skipped']} baris dilewati";
        }
        $msg .= '.';

        return redirect()->route('master-materials.index')
            ->with('success', $msg)
            ->with('import_stats', $result);
    }

    /**
     * Download template CSV for material import.
     */
    public function downloadTemplate(MaterialImportService $importService): Response
    {
        $content = $importService->generateCsvTemplate();

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_master_material.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
