<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\CompanyIncome;

class AdminInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('employee')->latest()->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('employee');
        return view('admin.invoices.show', compact('invoice'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_ref' => 'nullable|string',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        if ($invoice->status === 'paid') {
            return back()->withErrors(['message' => 'এই ইনভয়েসটি আগেই পেইড করা হয়েছে']);
        }

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('invoices/proofs', 'public');
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_ref' => $validated['payment_ref'] ?? null,
            'proof_file' => $proofPath,
        ]);

        // Add to company expenses
        \App\Models\Expense::create([
            'employee_id' => $invoice->employee_id,
            'title' => 'ইনভয়েস বিল পেমেন্ট: #' . $invoice->id . ($invoice->client_name ? ' (' . $invoice->client_name . ')' : ''),
            'amount' => $invoice->total_amount,
            'status' => 'approved',
            'payment_ref' => $validated['payment_ref'] ?? 'Invoice Payment',
            'proof_file' => $proofPath,
        ]);

        // Add to employee transactions
        \App\Models\Transaction::create([
            'employee_id' => $invoice->employee_id,
            'type' => 'payment',
            'amount' => $invoice->total_amount,
            'note' => 'ইনভয়েস পেমেন্ট: #' . $invoice->id,
            'transaction_date' => now(),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'ইনভয়েস পেইড হিসেবে মার্ক করা হয়েছে এবং ট্রানজেকশন/খরচে যুক্ত করা হয়েছে');
    }
}
