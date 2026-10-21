<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialOutbound extends Model
{
    use HasFactory;

    protected $fillable = [
        'outbound_code',
        'work_order_id',
        'ticket_id',
        'material_id',
        'spk_number',
        'ticket_number',
        'customer_name',
        'technician_name',
        'material_name',
        'quantity',
        'unit',
        'approved_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
