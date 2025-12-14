<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('_gabrielsv_blog_posts', 60 * 60, function () {
            try {
                $response = Http::timeout(5)->get('https://eu.gabrielsv.com/wp-json/wp/v2/posts?per_page=6&_embed');
                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                return [];
            }
        });

        return view('index', compact('posts'));
    }
}
