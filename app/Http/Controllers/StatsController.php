<?php

namespace App\Http\Controllers;

use App\Utils\SiteStats;
use Illuminate\Contracts\View\View;

class StatsController extends Controller
{
    public function index(): View
    {
        return view('pages.stats', [
            'stats' => SiteStats::fullStats(),
        ]);
    }
}
