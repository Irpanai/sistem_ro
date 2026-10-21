<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_number',
        'ticket_id',
        'technician_id',
        'technician_name',
        'technician_phone',
        'status',
        'tech_notes',
        'dispatched_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'dispatched_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function materialsOutbound(): HasMany
    {
        return $this->hasMany(MaterialOutbound::class);
    }
}
