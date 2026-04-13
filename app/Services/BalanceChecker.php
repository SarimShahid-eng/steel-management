<?php

namespace App\Services;

use App\Models\Account; // replace with your actual account model

class BalanceChecker
{
    /**
     * Check if the account has sufficient balance.
     *
     * @param int|Account $account Account ID or Account model instance
     * @param float $amount Amount to check
     * @return bool
     */
    public static function hasSufficientBalance($account, float $amount): bool
    {
        // Get account model if ID is passed
        if (! $account instanceof Account) {
            $account = Account::find($account);
            if (! $account) {
                return false; // account not found
            }
        }

        // Check if balance is enough
        return $account->balance >= $amount;
    }
}
