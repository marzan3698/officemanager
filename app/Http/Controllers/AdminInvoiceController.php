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

    public function pay(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->withErrors(['message' => 'এই ইনভয়েসটি আগেই পেইড করা হয়েছে']);
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Add to company expenses
        \App\Models\Expense::create([
            'employee_id' => $invoice->employee_id,
            'title' => 'ইনভয়েস বিল পেমেন্ট: #' . $invoice->id . ($invoice->client_name ? ' (' . $invoice->client_name . ')' : ''),
            'amount' => $invoice->total_amount,
            'status' => 'approved',
            'payment_ref' => 'Invoice Payment',
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
