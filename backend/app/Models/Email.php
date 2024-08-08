<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class Email extends Model
{
    use HasUuids;

    protected $table = 'emails';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'sender',
        'recipient',
        'subject',
        'body',
        'messageId',
        'inReplyTo',
        'from',
        'to',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


}
