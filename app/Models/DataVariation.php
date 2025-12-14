<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'service_id',
        'convinience_fee',
        'variation_code',
        'name',
        'variation_amount',
        'fixedPrice',
        'status',
    ];
}
