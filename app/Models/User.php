<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',      
        'avatar', 
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active'     => 'boolean',
        'last_login_at' => 'datetime',
        'password'      => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', '_id');
    }

    public function isAdmin(): bool
    {
        // dd($this->role->slug);
        return $this->role && in_array($this->role->slug, ['admin', 'super-admin']);
    }

    // public function isAdmin(): bool
    // {
    //     return in_array($this->role, ['admin', 'super_admin']);
    // }
}