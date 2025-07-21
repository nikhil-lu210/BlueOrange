<?php

namespace App\Http\Controllers\Administration\Vault;

use App\Exports\Administration\Vault\VaultExport;
use Exception;
use App\Models\Vault\Vault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Administration\Vault\VaultExportService;
use App\Http\Requests\Administration\Vault\VaultStoreRequest;
use App\Http\Requests\Administration\Vault\VaultUpdateRequest;

class VaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasAnyPermission(['Vault Everything', 'Vault Update', 'Vault Delete'])) {
            $vaults = Vault::with(['creator'])->orderBy('name', 'ASC')->get();
        } else {
            $vaults = Vault::with(['creator'])
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhereHas('viewers', function ($relation) use ($user) {
                            $relation->where('user_id', $user->id);
                        });
                })
                ->orderBy('name', 'ASC')
                ->get();
        }

        return view('administration.vault.index', compact(['vaults']));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->where('id', '!=', auth()->user()->id)
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        return view('administration.vault.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VaultStoreRequest $request)
    {
        // dd($request->all());
        try {
            DB::transaction(function() use ($request) {
                $vault = Vault::create([
                    'creator_id' => auth()->id(),
                    'name' => $request->name,
                    'url' => $request->url,
                    'username' => $request->username,
                    'password' => $request->password,
                    'note' => $request->note,
                ]);

                // Assign viewers to the vault if necessary
                if ($request->has('viewers')) {
                    $vault->viewers()->attach($request->viewers);
                }
            });

            toast('Credential Stored successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vault $vault)
    {
        // Check if the user has 'Vault Read' permission or is in the viewers list
        abort_if(
            (!auth()->user()->can('Vault Read') && !$vault->viewers->contains(auth()->user()->id)),
            403,
            'You do not have permission to view this vault.'
        );

        return view('administration.vault.show', compact(['vault']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vault $vault)
    {
        // Check if the user has 'Vault Update' permission or is in the viewers list
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->where('id', '!=', auth()->user()->id)
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        return view('administration.vault.edit', compact(['roles', 'vault']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VaultUpdateRequest $request, Vault $vault)
    {
        try {
            DB::transaction(function () use ($request, $vault) {
                $vault->update([
                    'name' => $request->name,
                    'url' => $request->url,
                    'username' => $request->username,
                    'password' => $request->password,
                    'note' => $request->note,
                ]);

                // Synchronize viewers (many-to-many relationship)
                if ($request->has('viewers')) {
                    $vault->viewers()->sync($request->viewers);
                } else {
                    $vault->viewers()->detach(); // Detach all viewers if none are provided
                }
            });

            toast('Credential updated successfully.', 'success');
            return redirect()->route('administration.vault.show', ['vault' => $vault]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vault $vault)
    {
        // dd($vault->toArray());
        try {
            $vault->delete();

            toast('Credential deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * export credentials.
     */
    public function export(VaultExportService $vaultExportService)
    {
        $user = auth()->user();

        if ($user->hasAnyPermission(['Vault Everything', 'Vault Update', 'Vault Delete'])) {
            $vaults = Vault::with(['creator'])->orderBy('name', 'ASC')->get();
        } else {
            $vaults = Vault::with(['creator'])
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhereHas('viewers', function ($relation) use ($user) {
                            $relation->where('user_id', $user->id);
                        });
                })
                ->orderBy('name', 'ASC')
                ->get();
        }

        try {
            $exportData = $vaultExportService->export($vaults);

            if (is_null($exportData)) {
                toast('There are no credentials to download.', 'warning');
                return redirect()->back();
            }

            // Return the Excel download with the appropriate filename
            return Excel::download(new VaultExport($exportData['vaults']), $exportData['fileName']);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
