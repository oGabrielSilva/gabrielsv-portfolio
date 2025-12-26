<?php

namespace App\Http\Controllers;

class CardGeneratorController extends Controller
{
    /**
     * Display the card generator page.
     */
    public function index()
    {
        return view('card-generator');
    }
}
