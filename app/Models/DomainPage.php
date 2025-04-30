<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainPage extends Model
{
    protected $fillable = [
        'domain',
        'page',
        'script',
        'title',
        'description',
        'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
