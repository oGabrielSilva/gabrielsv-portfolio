<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    public function show(LegalPage $page): View
    {
        return view('legal.show', [
            'page' => $page,
        ]);
    }
}
