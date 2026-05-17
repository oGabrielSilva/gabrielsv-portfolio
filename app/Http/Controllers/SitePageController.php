<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use Illuminate\Contracts\View\View;

class SitePageController extends Controller
{
    public function show(SitePage $page): View
    {
        return view('pages.site-page', ['page' => $page]);
    }
}
