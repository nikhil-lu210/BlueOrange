<?php

namespace App\Http\Controllers\Administration\Dashboard;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stevebauman\Location\Facades\Location;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publicIpAddress = $this->getPublicIpAddress();
        $location = Location::get($publicIpAddress);
        dd($publicIpAddress, $location);

        return view('administration.dashboard.index');
    }

    public function getPublicIpAddress()
    {
        $client = new Client();
        $response = $client->get('https://api64.ipify.org?format=json');

        $ipData = json_decode($response->getBody(), true);

        $publicIpAddress = $ipData['ip'] ?? null;

        return $publicIpAddress;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
