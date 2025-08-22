<?php

declare(strict_types=1);

namespace App\EloquentModels;

use App\Enum\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $invoice_id
 * @property string $description
 * @property int $quantity
 * @property float $unit_price
 */
class InvoiceItem extends EloquentModel
{
    public $timestamps = false;

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
