<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['entry', 'droppedPlayer', 'addedPlayer'])->when(request('entry_id'), function ($q) use ($request) {
            return $q->where('entry_id',$request->input('entry_id'));
        })->latest()->paginate(25);

        return view('transactions.index', [
            'transactions' => $transactions
        ]);
    }
}
