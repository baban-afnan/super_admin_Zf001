<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this import
use Illuminate\Database\Eloquent\Model;

class WebsiteClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone_number',
        'website_name',
        'website_link',
        'issue_date',
        'renew_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'renew_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->renew_date && $model->issue_date) {
                $model->renew_date = \Carbon\Carbon::parse($model->issue_date)->addYear();
            }
        });
    }
}
