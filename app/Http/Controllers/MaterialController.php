<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $query = Material::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            if ($request->condition === 'Menipis') {
                $query->whereColumn('stock', '<=', 'min_stock');
            } elseif ($request->condition === 'Aman') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        $materials = $query->orderBy('name')->get();
        $categories = Material::select('category')->distinct()->pluck('category');

        $totalSkus = Material::count();
        $lowStockCount = Material::whereColumn('stock', '<=', 'min_stock')->count();
        $healthyStockCount = Material::whereColumn('stock', '>', 'min_stock')->count();

        return view('logistik.index', compact(
            'materials',
            'categories',
            'totalSkus',
            'lowStockCount',
            'healthyStockCount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:materials,code',
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'price' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
        ]);

        Material::create($validated);

        return redirect()->route('logistik.index')
            ->with('success', "Material {$validated['name']} ({$validated['code']}) berhasil dicatat.");
    }

    public function restock(Request $request, Material $material): RedirectResponse
    {
        $request->validate([
            'added_quantity' => 'required|integer|min:1',
        ]);

        $added = (int) $request->added_quantity;
        $material->increment('stock', $added);

        return redirect()->route('logistik.index')
            ->with('success', "Stok {$material->name} bertambah +{$added} {$material->unit} (Total: {$material->stock}).");
    }
}
