<?php

declare(strict_types=1);

namespace App\EloquentModels;

use App\Enum\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $invoice_number
 * @property float $amount
 * @property InvoiceStatus $status
 * @property Carbon $created_at
 * @property Carbon $due_date
 */
class Invoice extends EloquentModel
{
    const null UPDATED_AT = null;

    protected $fillable = [
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'due_date' => 'datetime',
        'status' => InvoiceStatus::class
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
