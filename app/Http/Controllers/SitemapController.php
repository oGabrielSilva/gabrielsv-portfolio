<?php

namespace App\Http\Controllers;

use App\Providers\AppServiceProvider;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $now = now()->toAtomString();

        $urls = [
            ['loc' => url('/'),                  'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('tools.index'),      'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('brand-guide'),      'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('card-generator'),   'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        foreach (AppServiceProvider::TOOLS as $tool) {
            $urls[] = [
                'loc' => route($tool['route']),
                'priority' => '0.8',
                'changefreq' => 'monthly',
            ];
        }

        $xml = view('sitemap', ['urls' => $urls, 'lastmod' => $now])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }
}
