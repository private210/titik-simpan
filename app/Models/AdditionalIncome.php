<?php

namespace App\Models;

use App\Models\Concerns\ScopedByUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalIncome extends Model
{
    use HasFactory, ScopedByUser;

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'received_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_at' => 'date',
    ];
}
