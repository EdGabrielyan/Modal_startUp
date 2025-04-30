<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainPages extends Model
{
    protected $table = 'domain_pages';
    protected $guarded = false;

    public function domains():BelongsTo{
        return $this->belongsTo(Domains::class);
    }
}
