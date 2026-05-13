<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount',
        'type',
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
            'transaction_id' => 'integer',
            'account_id' => 'integer',
            'amount' => 'decimal:2',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function supplierAccount()
    {
        return $this->belongsTo(Account::class, 'account_id')->where('type', 'supplier');
    }

    public function customerAccount()
    {
        return $this->belongsTo(Account::class, 'account_id')->where('type', 'customer');
    }

    public function cashOrBankAccount()
    {
        return $this->belongsTo(Account::class, 'account_id')->whereIn('type', ['cash', 'bank']);
    }

    public function expenseAccount()
    {
        return $this->belongsTo(Account::class, 'account_id')->where('type', 'expense');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
