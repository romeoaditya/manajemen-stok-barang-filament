<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $items = Item::with('category', 'stocks','supplier')->get();
        $transactions = Transaction::with('transactionDetails')->get();

        return view('user.dashboard', [
            'items' => $items,
            'transactions' => $transactions,
        ]);
    }
}
