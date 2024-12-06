<?php

namespace App\Http\Controllers\Administration\Vault;

use App\Http\Controllers\Controller;
use App\Models\Vault\Vault;
use Illuminate\Http\Request;

class VaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administration.vault.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.vault.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Vault $vault)
    {
        dd($vault->toArray());
        return view('administration.vault.show', compact(['vault']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vault $vault)
    {
        dd($vault->toArray());
        return view('administration.vault.edit', compact(['vault']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vault $vault)
    {
        dd($vault->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vault $vault)
    {
        dd($vault->toArray());
    }
}
