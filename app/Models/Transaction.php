<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'voucher_no',
        'date',
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
            'date' => 'date',
        ];
    }
    public function getVoucherAttribute() {
    return match($this->type) {
        'purchase'   => 'PV-' . $this->voucher_no,
        'sale'       => 'SV-' . $this->voucher_no,
        'expense'    => 'EV-' . $this->voucher_no,
        'payment'    => 'PMT-' . $this->voucher_no,
        'adjustment' => 'ADJ-' . $this->voucher_no,
    };
}
}
