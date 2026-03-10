<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    const STATUS_DRAFT           = 'draft';
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_PAID            = 'paid';
    const STATUS_SIGNED          = 'signed';

    protected $fillable = [
        'user_id',
        'type',
        'landlord_name',
        'landlord_dui',
        'landlord_address',
        'tenant_name',
        'tenant_dui',
        'tenant_address',
        'property_address',
        'rent_amount',
        'deposit_amount',
        'duration_months',
        'start_date',
        'custom_clauses',
        'status',
        'pdf_path',
        'pdf_hash',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'rent_amount'     => 'decimal:2',
        'deposit_amount'  => 'decimal:2',
        'custom_clauses'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }

    public function landlordSignature(): HasOne
    {
        return $this->hasOne(Signature::class)->where('signer_role', 'landlord');
    }

    public function tenantSignature(): HasOne
    {
        return $this->hasOne(Signature::class)->where('signer_role', 'tenant');
    }

    public function getEndDateAttribute()
    {
        return $this->start_date->addMonths($this->duration_months);
    }

    public function isPaid(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_SIGNED]);
    }

    public function isDownloadable(): bool
    {
        return $this->isPaid() && $this->pdf_path !== null;
    }
}
