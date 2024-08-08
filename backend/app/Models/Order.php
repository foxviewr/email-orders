<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Order extends Model
{
    use HasUuids;

    protected $table = 'orders';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'number',
        'status',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }
}
