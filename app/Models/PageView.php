<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'route_name',
        'referrer_host',
        'device',
        'is_bot',
        'country',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'viewed_at',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'viewed_at' => 'datetime',
    ];
}
