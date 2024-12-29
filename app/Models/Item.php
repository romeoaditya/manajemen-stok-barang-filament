<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_code',
        'item_name',
        'category_id',
        'satuan',
        'stok_awal',
        'deskripsi',
        'photo',
        'supplier_id',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    // Item.php
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    public function stocks(): HasMany 
    {
        return $this->hasMany(Stock::class);
    }
}
