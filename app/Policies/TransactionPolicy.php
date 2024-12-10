<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->entry->user_id || $user->isAdmin();
    }
}