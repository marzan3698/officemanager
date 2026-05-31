<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class EmployeeExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('employee_id', auth()->id())->latest()->get();
        return view('employee.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('employee.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'items' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        $validated['employee_id'] = auth()->id();
        $validated['status'] = 'pending';

        if ($request->hasFile('invoice_file')) {
            $validated['invoice_file'] = $request->file('invoice_file')->store('expenses/invoices', 'public');
        }

        // Store items JSON in note field, append user note
        $items = json_decode($validated['items'], true);
        $itemsSummary = '';
        if (is_array($items)) {
            foreach ($items as $i => $item) {
                $itemsSummary .= ($i + 1) . '. ' . $item['name'] . ' — ' . $item['qty'] . 'x @ ' . $item['price'] . '৳ = ' . ($item['qty'] * $item['price']) . "৳\n";
            }
        }
        if (!empty($validated['note'])) {
            $itemsSummary .= "\nনোট: " . $validated['note'];
        }
        $validated['note'] = $itemsSummary;
        unset($validated['items']);

        Expense::create($validated);
        
        return redirect('/employee/expenses')->with('success', 'ইনভয়েস সফলভাবে জমা দেওয়া হয়েছে');
    }
}
