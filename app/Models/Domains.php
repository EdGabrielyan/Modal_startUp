<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domains extends Model
{
    protected $table = 'domains';
    protected $guarded = false;

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function domainPages(): HasMany{
        return $this->hasMany(DomainPages::class);
    }

}
