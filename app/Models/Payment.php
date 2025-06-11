<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['merchant_id', 'payment_method_id', 'status_id', 'payment_date'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}