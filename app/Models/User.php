<?php

namespace App\Models;

use App\Models\user\UserStatues;
use App\Models\UserStatues as ModelsUserStatues;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Correct Parent Class
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // ✅ Extend Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable; // ✅ Use Traits for Authentication

    protected $fillable = ['name', 'email', 'password', 'status_id','role_id'];

    protected $hidden = ['password', 'remember_token']; // ✅ Hide Sensitive Data

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
