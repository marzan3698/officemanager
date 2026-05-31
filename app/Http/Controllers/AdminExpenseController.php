<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class AdminExpenseController extends Controller
{
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'payment_ref' => 'required_if:status,approved|nullable|string',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        $expense->status = $validated['status'];
        
        if ($validated['status'] === 'approved') {
            $expense->payment_ref = $validated['payment_ref'];
            if ($request->hasFile('proof_file')) {
                $expense->proof_file = $request->file('proof_file')->store('expenses/proofs', 'public');
            }
        }
        
        $expense->save();
        
        return redirect()->back()->with('success', 'ইনভয়েস স্ট্যাটাস আপডেট করা হয়েছে');
    }
}
