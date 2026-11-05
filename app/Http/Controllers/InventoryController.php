<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\InventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request): View
    {
        // Check permissions
        if (auth()->check() && method_exists(auth()->user(), 'hasPermission') && !auth()->user()->hasPermission('inventory.view')) {
            abort(403, 'Bu sayfayı görme yetkiniz yok.');
        }

        $query = InventoryItem::with(['creator', 'updater']);

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Whitelist Sort by & Sort order
        $allowedSortColumns = ['id', 'name', 'title', 'code', 'sku', 'category', 'price', 'unit_cost', 'quantity', 'stock', 'status', 'created_at'];
        $sortBy = $request->get('sort_by', 'name');
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'name';
        }
        $sortOrder = strtolower($request->get('sort_direction', $request->get('sort_order', 'asc')));
        if (!in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'asc';
        }
        $query->orderBy($sortBy, $sortOrder);

        $items = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => InventoryItem::count(),
            'active' => InventoryItem::whereIn('status', ['active', 'Aktif'])->count(),
            'low_stock' => InventoryItem::whereRaw('quantity <= min_quantity')->count(),
            'total_value' => InventoryItem::selectRaw('SUM(quantity * price) as total')->value('total') ?? 0,
        ];

        // Get categories for filter dropdown
        $categories = InventoryItem::distinct()->pluck('category')->filter();

        return view('inventory.index', compact('items', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create(): View
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $code = $validated['code'] ?? $validated['sku'] ?? ('INV-' . strtoupper(Str::random(6)));
        $price = $validated['price'] ?? $validated['unit_price'] ?? 0;
        $statusMap = ['Aktif' => 'active', 'Pasif' => 'inactive', 'Bakımda' => 'maintenance'];
        $rawStatus = $validated['status'] ?? 'active';
        $status = $statusMap[$rawStatus] ?? $rawStatus;

        $item = new InventoryItem();
        $item->forceFill([
            'name' => $validated['name'],
            'title' => $validated['name'],
            'code' => $code,
            'sku' => $validated['sku'] ?? $code,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'quantity' => $validated['quantity'],
            'stock' => $validated['quantity'],
            'min_quantity' => $validated['min_quantity'] ?? 0,
            'price' => $price,
            'unit_price' => $price,
            'unit_cost' => $price,
            'unit' => $validated['unit'] ?? 'Adet',
            'supplier' => $validated['supplier'] ?? null,
            'location' => $validated['location'] ?? null,
            'status' => $status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
        $item->save();

        return redirect()->route('inventory.index')
            ->with('success', 'Envanter öğesi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(string $id): View
    {
        $item = InventoryItem::findOrFail($id);

        return view('inventory.show', compact('item'));
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(string $id): View
    {
        $item = InventoryItem::findOrFail($id);

        return view('inventory.edit', compact('item'));
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, string $id)
    {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $code = $validated['code'] ?? $validated['sku'] ?? $item->code ?? $item->sku;
        $price = $validated['price'] ?? $validated['unit_price'] ?? $item->price ?? 0;
        $statusMap = ['Aktif' => 'active', 'Pasif' => 'inactive', 'Bakımda' => 'maintenance'];
        $rawStatus = $validated['status'] ?? $item->getRawOriginal('status') ?? 'active';
        $status = $statusMap[$rawStatus] ?? $rawStatus;

        $item->forceFill([
            'name' => $validated['name'],
            'title' => $validated['name'],
            'code' => $code,
            'sku' => $validated['sku'] ?? $code,
            'description' => $validated['description'] ?? $item->description,
            'category' => $validated['category'],
            'quantity' => $validated['quantity'],
            'stock' => $validated['quantity'],
            'min_quantity' => $validated['min_quantity'] ?? $item->min_quantity ?? 0,
            'price' => $price,
            'unit_price' => $price,
            'unit_cost' => $price,
            'unit' => $validated['unit'] ?? $item->unit,
            'supplier' => $validated['supplier'] ?? $item->supplier,
            'location' => $validated['location'] ?? $item->location,
            'status' => $status,
            'updated_by' => auth()->id(),
        ]);
        $item->save();

        return redirect()->route('inventory.index')
            ->with('success', 'Envanter öğesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(string $id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Envanter öğesi başarıyla silindi.');
    }

    /**
     * Display low-stock inventory items.
     */
    public function lowStock(Request $request): View
    {
        $items = InventoryItem::whereRaw('COALESCE(quantity, stock, 0) <= COALESCE(min_quantity, 0)')
            ->orderBy('quantity', 'asc')
            ->paginate(20);

        $stats = [
            'total' => InventoryItem::count(),
            'active' => InventoryItem::whereIn('status', ['active', 'Aktif'])->count(),
            'low_stock' => InventoryItem::whereRaw('COALESCE(quantity, stock, 0) <= COALESCE(min_quantity, 0)')->count(),
            'total_value' => InventoryItem::selectRaw('SUM(COALESCE(quantity, stock, 0) * COALESCE(price, unit_cost, 0)) as total')->value('total') ?? 0,
        ];

        $categories = InventoryItem::distinct()->pluck('category')->filter();

        return view('inventory.index', compact('items', 'stats', 'categories'));
    }

    /**
     * Export inventory items as CSV.
     */
    public function export()
    {
        $items = InventoryItem::orderBy('name')->get();
        $csv = "Kod,Ad,Kategori,Miktar,Birim,Birim Fiyat,Durum\n";
        foreach ($items as $item) {
            $code = str_replace('"', '""', $item->code ?? $item->sku ?? '');
            $name = str_replace('"', '""', $item->name ?? $item->title ?? '');
            $cat = str_replace('"', '""', $item->category ?? '');
            $qty = $item->quantity ?? $item->stock ?? 0;
            $unit = str_replace('"', '""', $item->unit ?? 'Adet');
            $price = $item->price ?? $item->unit_cost ?? 0;
            $status = str_replace('"', '""', $item->status ?? 'active');
            $csv .= "\"{$code}\",\"{$name}\",\"{$cat}\",{$qty},\"{$unit}\",{$price},\"{$status}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="envanter_listesi.csv"',
        ]);
    }
}
