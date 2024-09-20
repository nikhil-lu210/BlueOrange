<?php

namespace App\Http\Controllers\Administration\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pick a random birthday wish
        $wish = $this->randomBirthdayWish();

        return view('administration.dashboard.index', compact('wish'));
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



    private function randomBirthdayWish()
    {
        // Predefined array of birthday wishes
        $birthdayWishes = [
            "May your birthday be the start of a year filled with good luck, good health, and much happiness.",
            "Enjoy your special day with all the people you love.",
            "Wishing you a day that’s as special as you are!",
            "This day is as special as you are. Your contributions to our team are invaluable, and your positive energy always brightens our day. Thank you for your hard work and dedication. May this year bring you even greater success and happiness. Enjoy your special day with loved ones and make some unforgettable memories. Cheers to another year of growth and achievement!",
            "May your birthday be a day filled with laughter, love, and all the things that make you happy. May the year ahead be filled with joy, success, and endless possibilities.",
            "Happy birthday to a truly exceptional person. You inspire us all with your kindness, intelligence, and unwavering spirit. May this day be as special as you are.",
            "As you celebrate another year of life, we want to express our sincere gratitude for your friendship and support. You make a positive impact on everyone around you, and we're lucky to have you in our lives. Happy birthday!",
            "May your birthday be a day filled with sunshine, laughter, and love. May the year ahead be filled with new adventures, exciting opportunities, and endless joy. Happy birthday!",
            "Happy birthday to a remarkable individual. You are a source of inspiration, a pillar of strength, and a true friend. May this day be filled with happiness and love, and may the year ahead be filled with success and fulfillment. Cheers to you!",
            "Wishing you a birthday that is as bright and beautiful as you are. May your day be filled with joy, laughter, and love from all the people who care about you. Happy birthday!",
            "As you celebrate another year of life, we want to express our gratitude for your friendship and your contributions to our team. You are a valued member of our community, and we are lucky to have you. Happy birthday!",
            "May your birthday be a day filled with peace, love, and happiness. May the year ahead be filled with good health, prosperity, and success. Happy birthday!",
            "Happy birthday to a truly amazing person. You are a source of inspiration, a pillar of strength, and a true friend. May this day be filled with happiness and love, and may the year ahead be filled with success and fulfillment. Cheers to you!"
        ];

        // Return a random birthday wish
        return $birthdayWishes[array_rand($birthdayWishes)];
    }
}
