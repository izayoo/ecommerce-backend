<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Ticket extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at'];

    protected $fillable = ['order_product_id', 'ticket_no', 'status'];

    public function orderProduct(): BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'id');
    }

    public function order(): HasOneThrough
    {
        return $this->hasOneThrough(
            Order::class, OrderProduct::class,
            'id', 'id',
            'order_product_id', 'order_id');
    }
}
