<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'order', 'icon'];
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
