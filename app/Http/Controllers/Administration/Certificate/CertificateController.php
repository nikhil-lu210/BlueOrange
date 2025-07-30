<?php

namespace App\Http\Controllers\Administration\Certificate;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = [];
        return view('administration.certificate.index', compact(['certificates']));
    }

    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $certificates = [];
        return view('administration.certificate.my', compact(['certificates']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::select(['id', 'name'])->orderBy('name')->get();

        return view('administration.certificate.create', compact(['employees']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function generate(Request $request)
    {
        $certificate = [
            'user_id' => $request->user_id,
            'type' => $request->type,
            'issue_date' => $request->issue_date,

            'joining_date' => $request->joining_date, // Required for Appointment Letter
            'salary' => $request->salary, // Required for Appointment Letter and Employment Certificate
            'resignation_date' => $request->resignation_date, // Required for Experience Letter
            'release_date' => $request->release_date, // Required for Release Letter
            'release_reason' => $request->release_reason, // Required for Release Letter
            'country_name' => $request->country_name, // Required for NOC/No Objection Letter
            'visiting_purpose' => $request->visiting_purpose, // Required for NOC/No Objection Letter
            'leave_starts_from' => $request->leave_starts_from, // Required for NOC/No Objection Letter
            'leave_ends_on' => $request->leave_ends_on, // Optional for NOC/No Objection Letter
        ];

        return view('administration.certificate.create', compact(['certificate']));
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
    public function show(string $id)
    {
        return view('administration.certificate.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('administration.certificate.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
