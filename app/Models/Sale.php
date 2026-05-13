<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_account_id',
        'transaction_id',
        'total_amount',
        'received_amount',
        'remaining_amount',
        'date',
        'payment_account_id',
        // 'customer_account_id_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'customer_account_id' => 'integer',
            'transaction_id' => 'integer',
            'total_amount' => 'decimal:2',
            'received_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function customerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function receivedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'payment_account_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function accountEntries()
    {
        return $this->hasMany(AccountEntry::class, 'transaction_id','transaction_id');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
