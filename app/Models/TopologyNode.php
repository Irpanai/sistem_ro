<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopologyNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'name',
        'type',
        'area',
        'location',
        'coordinates',
        'total_ports',
        'used_ports',
        'status',
        'connected_users',
        'connected_odp',
        'issue',
    ];

    protected function casts(): array
    {
        return [
            'total_ports' => 'integer',
            'used_ports' => 'integer',
            'connected_users' => 'integer',
            'connected_odp' => 'integer',
        ];
    }
}
