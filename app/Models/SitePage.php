<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = ['slug', 'title', 'subtitle', 'address', 'body_html', 'meta_description'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
