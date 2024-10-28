<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'draft',
        'paid_at',

        'series',
        // 'number',

        'subtotal',
        'tax',

        'client_id',
        'client_name',
        'client_tax_id',
        'client_address',

        'vendor_name',
        'vendor_tax_id',
        'vendor_address',

        'user_id'
    ];

    protected $visible = [
        'id',
        'date',
        'draft',
        'paid_at',

        'series',
        'number',

        'subtotal',
        'tax',

        'client_id',
        'client_name',
        'client_tax_id',
        'client_address',

        'vendor_name',
        'vendor_tax_id',
        'vendor_address',

        'paid',
        'total'
    ];

    protected $appends = [
        'paid',
        'total'
    ];

    protected function paid(): Attribute
    {
        return new Attribute(
            get: fn() => $this->paid_at ? true : false,
            // set: fn(mixed $value) => [
            //     'paid_at' => $value  == true? new \DateTime()
            // ]
            set: function (mixed $value, array $attributes) {
                // if (!$attributes['draft'] && $value == true) {
                // }
                // return [];
                return ['paid_at' => $value == true ? new \DateTime() : null];
            }
        );
    }

    protected function total(): Attribute
    {
        return new Attribute(get: fn() => $this->subtotal + $this->tax);
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            $invoice->series = strtoupper($invoice->series);

            if ($invoice->draft) {
                return;
            }

            $lastActiveInvoice = Invoice::where('user_id', '=', $invoice->user_id)
                ->where('draft', '=', false)
                ->where('series', '=', $invoice->series)
                ->select('number')
                ->first();
            $invoice->number = $lastActiveInvoice ? $lastActiveInvoice->number + 1 : 1;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
