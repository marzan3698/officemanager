<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class EmployeeInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('employee_id', auth()->id())->latest()->get();
        return view('employee.invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('employee.invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'nullable|string',
            'client_phone' => 'nullable|string',
            'items' => 'required|string', // Will be JSON string from frontend
        ]);

        $items = json_decode($request->items, true);
        if (!$items || !is_array($items) || count($items) === 0) {
            return back()->withErrors(['items' => 'কমপক্ষে একটি আইটেম যোগ করুন']);
        }

        $totalAmount = 0;
        foreach ($items as &$item) {
            $item['qty'] = (int) $item['qty'];
            $item['price'] = (float) $item['price'];
            $item['total'] = $item['qty'] * $item['price'];
            $totalAmount += $item['total'];
        }

        Invoice::create([
            'employee_id' => auth()->id(),
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'items' => $items,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);

        return redirect('/employee/invoices')->with('success', 'ইনভয়েস তৈরি করা হয়েছে');
    }
}
