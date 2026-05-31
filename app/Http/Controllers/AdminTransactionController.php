<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('employee')->latest('transaction_date')->get();
        $totalTransactions = $transactions->count();
        return view('admin.transactions.index', compact('transactions', 'totalTransactions'));
    }

    public function create()
    {
        $employees = User::where('role', 'employee')->where('is_active', true)->get();
        return view('admin.transactions.create', compact('employees'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('employee');
        return view('admin.transactions.show', compact('transaction'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'type' => 'required|in:payment,deduction,bonus',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'note' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);
        
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('invoice_file')) {
            $path = $request->file('invoice_file')->store('invoices', 'public');
            $validated['invoice_file'] = $path;
        }

        Transaction::create($validated);
        
        return redirect('/admin/transactions')->with('success', 'লেনদেন যোগ করা হয়েছে');
    }

    public function edit(Transaction $transaction)
    {
        $employees = User::where('role', 'employee')->where('is_active', true)->get();
        return view('admin.transactions.edit', compact('transaction', 'employees'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'type' => 'required|in:payment,deduction,bonus',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'note' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($request->hasFile('invoice_file')) {
            $path = $request->file('invoice_file')->store('invoices', 'public');
            $validated['invoice_file'] = $path;
        }

        $transaction->update($validated);
        
        return redirect('/admin/transactions/' . $transaction->id)->with('success', 'লেনদেন আপডেট করা হয়েছে');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect('/admin/transactions')->with('success', 'লেনদেন মুছে ফেলা হয়েছে');
    }
}
