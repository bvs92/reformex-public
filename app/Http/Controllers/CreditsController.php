<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class CreditsController extends Controller
{

    public function index()
    {
        $activities = auth()->user()->activities()->latest()->get();
        $transactions = auth()->user()->transactions()->latest()->get();
        $payments = auth()->user()->payments()->latest()->get();
        $total_amount = auth()->user()->payments->sum('amount') / 100;
        $total_cost = auth()->user()->activities->sum('amount');
        $last_payment = auth()->user()->payments->last();
        // dd($total_amount);

        return view('volgh.credits.index', [
            'amount' => auth()->user()->getCreditAmount(),
            'activities' => $activities,
            'transactions' => $transactions,
            'payments' => $payments,
            'total_amount' => $total_amount ?? '0',
            'total_cost' => $total_cost ?? '0',
            'last_payment' => $last_payment ?? '0',
        ]);
    }

    public function personal()
    {
        $user = auth()->user();
        if (!$user || !$user->isPro()) {
            return redirect()->back();
        }

        $last_payment = auth()->user()->payments->last();
        // dd($total_amount);

        return view('volgh.credits.personal', [
            'amount' => auth()->user()->getCreditAmount(),
            'last_payment' => $last_payment ?? false,
        ]);
    }

    public function simple()
    {
        $user = auth()->user();
        if (!$user || !$user->isPro()) {
            return redirect()->back();
        }

        return view('volgh.credits.simple', [
            'amount' => auth()->user()->getCreditAmount(),
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'credit' => 'required|numeric|min:50',
        ]);

        $credit = auth()->user()->credit;
        $credit->amount += $validated['credit'];

        if (!$credit->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        Transaction::create([
            'user_id' => auth()->user()->id,
            'description' => 'Alimentare Cont cu cardul.',
            'amount' => $validated['credit'],
        ]);

        return redirect()->route('credits.index')->with('success', 'Operatie efectuata cu succes.');

        // dd($request->all());
    }
}
