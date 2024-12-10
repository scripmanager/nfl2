<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['entry', 'droppedPlayer', 'addedPlayer'])
            ->latest()
            ->paginate(25);

        return view('transactions.index', [
            'transactions' => $transactions
        ]);
    }
}