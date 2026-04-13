<?php

namespace App\Services;

use App\Models\Transaction;

class VoucherService
{
    // Map transaction type → prefix
    private const PREFIXES = [
        'purchase'   => 'PV',
        'sale'       => 'SV',
        'expense'    => 'EV',
        'payment'    => 'PMT',
        'capitalDeposit'=>'CPT',
        'adjustment' => 'ADJ',
    ];

    /**
     * Get next voucher number for a given transaction type.
     * Stored as plain integer in DB. Prefix is display-only.
     *
     * Usage:
     *   $voucher->next('purchase')   → 1, 2, 3 ...
     *   $voucher->display('purchase', 3) → "PV-3"
     */
    public function next(string $type): int
    {
        $last = Transaction::where('type', $type)
            ->whereNotNull('voucher_no')
            ->max('voucher_no');

        return ($last ?? 0) + 1;
    }

    /**
     * Format a voucher number for display.
     *
     * $voucher->display('purchase', 3)  → "PV-3"
     * $voucher->display('sale', 12)     → "SV-12"
     */
    public function display(string $type, int $number): string
    {
        $prefix = self::PREFIXES[$type] ?? strtoupper($type);
        return "{$prefix}-{$number}";
    }

    /**
     * Get the next formatted display voucher (useful for showing in create forms).
     *
     * $voucher->nextDisplay('purchase')  → "PV-4"
     */
    public function nextDisplay(string $type): string
    {
        return $this->display($type, $this->next($type));
    }
}
