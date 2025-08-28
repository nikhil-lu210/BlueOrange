<?php

namespace App\Http\Controllers\Administration\Inventory;

use Exception;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryCategory;
use App\Http\Requests\Administration\Inventory\InventoryStoreRequest;
use App\Http\Requests\Administration\Inventory\InventoryUpdateRequest;
use App\Services\Administration\Inventory\InventoryService;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function inventoryCategory()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::with('category')->get();
        return view('administration.inventory.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InventoryCategory::select(['id', 'name'])->get();
        $purposes = Inventory::query()->distinct()->pluck('usage_for')->toArray();

        return view('administration.inventory.create', compact('categories', 'purposes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryStoreRequest $request)
    {
        try {
            $this->inventoryService->storeInventory($request);

            toast('Inventory items created successfully.', 'success');
            return redirect()->route('administration.inventory.index');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('administration.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $categories = InventoryCategory::select(['id', 'name'])->get();
        $purposes = Inventory::query()->distinct()->pluck('usage_for')->toArray();

        return view('administration.inventory.edit', compact('inventory', 'categories', 'purposes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryUpdateRequest $request, Inventory $inventory)
    {
        try {
            $this->inventoryService->updateInventory($request, $inventory);

            toast('Inventory updated successfully.', 'success');
            return redirect()->route('administration.inventory.show', ['inventory' => $inventory]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update inventory status.
     */
    public function statusUpdate(Request $request, Inventory $inventory)
    {
        try {
            $request->validate([
                'status' => 'required|in:Available,In Use,Out of Service,Damaged'
            ]);

            $this->inventoryService->updateInventoryStatus($inventory, $request->status);

            toast('Inventory status updated successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        try {
            $this->inventoryService->deleteInventory($inventory);

            toast('Inventory deleted successfully.', 'success');
            return redirect()->route('administration.inventory.index');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
