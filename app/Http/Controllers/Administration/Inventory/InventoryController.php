<?php

namespace App\Http\Controllers\Administration\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
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
        return view('administration.inventory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
