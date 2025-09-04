<?php

namespace App\Http\Controllers\Administration\FunctionalityWalkthrough;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthrough;
use App\Services\Administration\FunctionalityWalkthrough\FunctionalityWalkthroughService;
use App\Http\Requests\Administration\FunctionalityWalkthrough\WalkthroughStoreRequest;
use App\Http\Requests\Administration\FunctionalityWalkthrough\WalkthroughUpdateRequest;

class FunctionalityWalkthroughController extends Controller
{
    protected $walkthroughService;

    public function __construct(FunctionalityWalkthroughService $walkthroughService)
    {
        $this->walkthroughService = $walkthroughService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->walkthroughService->getRolesForDropdown();
        $walkthroughs = $this->walkthroughService->getWalkthroughsForIndex($request);

        return view('administration.functionality_walkthrough.index', compact(['roles', 'walkthroughs']));
    }

    /**
     * Display user's walkthroughs.
     */
    public function my()
    {
        $walkthroughs = $this->walkthroughService->getWalkthroughsForUser();

        return view('administration.functionality_walkthrough.my', compact(['walkthroughs']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->walkthroughService->getRolesForDropdown();

        return view('administration.functionality_walkthrough.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WalkthroughStoreRequest $request)
    {
        try {
            $this->walkthroughService->createWalkthrough($request->validated());

            toast('Functionality Walkthrough created successfully.', 'success');
            return redirect()->route('administration.functionality_walkthrough.index');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the walkthrough: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        if ($functionalityWalkthrough->isAuthorized() == false) {
            abort(403, "You do not have permission to view this walkthrough ({$functionalityWalkthrough->title}).");
        }

        // Mark as read for current user
        $this->walkthroughService->markAsRead($functionalityWalkthrough, Auth::id());

        $walkthrough = $this->walkthroughService->getWalkthroughWithRelations($functionalityWalkthrough);

        return view('administration.functionality_walkthrough.show', compact('walkthrough'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        $roles = $this->walkthroughService->getRolesForDropdown();
        $walkthrough = $this->walkthroughService->getWalkthroughWithRelations($functionalityWalkthrough);

        return view('administration.functionality_walkthrough.edit', compact(['walkthrough', 'roles']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WalkthroughUpdateRequest $request, FunctionalityWalkthrough $functionalityWalkthrough)
    {
        try {
            $this->walkthroughService->updateWalkthrough($functionalityWalkthrough, $request->validated());

            toast('Functionality Walkthrough updated successfully.', 'success');
            return redirect()->route('administration.functionality_walkthrough.show', ['functionalityWalkthrough' => $functionalityWalkthrough]);
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while updating the walkthrough: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        try {
            $this->walkthroughService->deleteWalkthrough($functionalityWalkthrough);

            toast('Functionality Walkthrough deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
