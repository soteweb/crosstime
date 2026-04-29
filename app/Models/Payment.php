<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'amount',
        'payment_method',
        'reference',
        'notes',
        'payment_date',
        'receipt_image'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
