<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'store_id', 'status', 'total_amount', 'order_date'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function store()
    // {
    //     return $this->belongsTo(Store::class);
    // }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // public function payment()
    // {
    //     return $this->hasOne(Payment::class);
    // }

    // public function collection()
    // {
    //     return $this->hasOne(Collection::class);
    // }
}
