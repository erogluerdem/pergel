<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\InventoryItem;
use Illuminate\Http\RedirectResponse;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request): View
    {
        // Check permissions
        if (!auth()->user()->hasPermission('inventory.view')) {
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
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $items = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => InventoryItem::count(),
            'active' => InventoryItem::where('status', 'active')->count(),
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
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:inventory_items,code',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Pasif,Bakımda'
        ]);

        // In real application, save to database
        // InventoryItem::create($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Envanter öğesi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(string $id): View
    {
        $item = \App\Models\InventoryItem::findOrFail($id);

        return view('inventory.show', compact('item'));
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(string $id): View
    {
        $item = \App\Models\InventoryItem::findOrFail($id);

        return view('inventory.edit', compact('item'));
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Pasif,Bakımda'
        ]);

        // In real application, update in database
        // InventoryItem::findOrFail($id)->update($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Envanter öğesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(string $id)
    {
        // In real application, delete from database
        // InventoryItem::findOrFail($id)->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Envanter öğesi başarıyla silindi.');
    }
}