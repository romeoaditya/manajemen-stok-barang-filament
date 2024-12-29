<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'quantity',
        'total_amount',
        'is_paid',
        'proof_payment',
        'item_id',
    ];
    protected static function booted()
{
    static::created(function ($transaction) {
        if ($transaction->item_id && $transaction->quantity) {
            $item = Item::find($transaction->item_id);
            if ($item) {
                $item->stok_awal -= $transaction->quantity;
                $item->save();
            }
        }
    });
}

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
