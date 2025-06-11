<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory,SoftDeletes ;
    protected $fillable = ['view', 'group_id','module_id','role_id'];

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
