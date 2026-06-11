<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'user_id', 'number', 'total_price', 'payment_status', 'order_status', 'snap_token', 'shipping_address', 'shipping_cost'
    ];

    //Order punya banyak item
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    //Order milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
