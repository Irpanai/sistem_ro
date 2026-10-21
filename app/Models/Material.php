<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'stock',
        'min_stock',
        'unit',
        'price',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'min_stock' => 'integer',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}
