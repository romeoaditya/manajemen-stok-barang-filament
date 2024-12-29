<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'quantity',
    ];
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    protected static function booted()
    {
        // Saat stok diubah (edit)
        static::updating(function ($stock) {
            $originalQuantity = $stock->getOriginal('quantity'); // Dapatkan jumlah stok sebelum diubah
            $newQuantity = $stock->quantity; // Jumlah stok baru

            $difference = $newQuantity - $originalQuantity;

            if ($stock->item) {
                $stock->item->increment('stok_awal', $difference);
            }
        });

        // Saat stok baru dibuat
        static::created(function ($stock) {
            if ($stock->item) {
                $stock->item->increment('stok_awal', $stock->quantity);
            }
        });

        // Saat stok dihapus
        static::deleted(function ($stock) {
            if ($stock->item) {
                $stock->item->decrement('stok_awal', $stock->quantity);
            }
        });
    }
}
