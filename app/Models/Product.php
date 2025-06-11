<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory ,SoftDeletes;

    protected $fillable = ['name', 'description', 'price', 'store_id','image'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
