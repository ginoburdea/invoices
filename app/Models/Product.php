<?php

namespace App\Models;

use App\Events\ProductCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'tax_percentage',
        'user_id',
        'invoice_id',
        'parent_id',
    ];
    protected $visible = [
        'id',
        'name',
        'sku',
        'price',
        'tax_percentage',
        'user'
    ];

    // protected $dispatchesEvents = [
    //     'created' => ProductCreated::class,
    // ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
