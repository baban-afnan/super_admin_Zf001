<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic info
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone_no',
        'address',
        'photo',
        'pin',
        'profile_photo_url',

        // Identity
        'bvn',
        'nin',
        'tin',
        'state',
        'lga',
        'nearest_bus_stop',
        'business_name',

        // Referral system
        'referral_code',
        'referral_bonus',
        'referred_by',
        'performed_by',
        'approved_by',

        // Other
        'claim_id',
        'status',
        'limit',
        'role',
        'password',
        'api_token',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'two_factor_expires_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
        ];
    }


    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (empty($this->photo)) {
            return asset('assets/img/profiles/avatar-31.jpg');
        }

        if (strpos($this->photo, 'http') === 0) {
            return $this->photo;
        }

        if (strpos($this->photo, 'storage/') === 0) {
            return asset($this->photo);
        }

        // Check if file exists in public/ directly (legacy)
        try {
            if (file_exists(public_path($this->photo))) {
                return asset($this->photo);
            }
        } catch (\Exception $e) {
            // Fallback for environments where public_path might fail or be restricted
        }

        // Default to storage path
        return asset('storage/' . ltrim($this->photo, '/'));
    }

    /**
     * Get the user's display first name.
     */
    public function getDisplayFirstNameAttribute(): string
    {
        return $this->first_name ?? ($this->name ? explode(' ', $this->name)[0] : 'User');
    }

    /**
     * Get the user's display last name.
     */
    public function getDisplayLastNameAttribute(): string
    {
        return $this->last_name ?? ($this->name ? implode(' ', array_slice(explode(' ', $this->name), 1)) : '');
    }

    /**
     * Relationship: A user has many transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relationship: A user has one wallet
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Relationship: A user has many API applications
     */
    public function apiApplications()
    {
        return $this->hasMany(ApiApplication::class);
    }

    /**
     * Relationship: A user has one virtual account
     */
    public function virtualAccount()
    {
        return $this->hasOne(VirtualAccount::class);
    }
}
