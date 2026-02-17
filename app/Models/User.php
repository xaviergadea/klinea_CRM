<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar', 'last_login_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'assigned_to');
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'assigned_to');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
