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

        return back()->with('success', 'ইনভয়েস পেইড হিসেবে মার্ক করা হয়েছে এবং কোম্পানির খরচে যুক্ত করা হয়েছে');
    }
}
