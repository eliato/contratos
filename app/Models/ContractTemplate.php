<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'default_clauses',
        'price',
        'is_active',
    ];

    protected $casts = [
        'default_clauses' => 'array',
        'price'           => 'decimal:2',
        'is_active'       => 'boolean',
    ];

    public static function active()
    {
        return static::where('is_active', true)->orderBy('name');
    }
}
