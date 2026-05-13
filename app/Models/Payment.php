<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'account_id',
        'payment_account_id',
        'transaction_id',
        'type',
        'amount',
        'description',
        'date',
    ];

    public function supplierAccounts()
    {
        return $this->belongsTo(Account::class, 'account_id')->where('type', 'supplier');
    }
    public function customerAccounts()
    {
        return $this->belongsTo(Account::class, 'account_id')->where('type', 'customer');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    public function paymentAccounts()
    {
        return $this->belongsTo(Account::class, 'payment_account_id')->whereIn('type', ['cash', 'bank']);
    }

    public function accountEntries()
    {
        return $this->hasMany(AccountEntry::class, 'transaction_id', 'transaction_id');
    }
}
