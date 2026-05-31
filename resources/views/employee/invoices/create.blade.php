@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex align-center">
        <a href="/employee/invoices" style="margin-right: 16px; font-size: 20px;">⬅</a>
        <h1>নতুন ইনভয়েস তৈরি করুন (POS)</h1>
    </div>
</div>

<div class="content">
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/employee/invoices" id="invoiceForm">
        @csrf
        <input type="hidden" name="items" id="itemsInput">

        <x-card>
            <div style="font-weight: 600; margin-bottom: 12px; font-size: 16px;">ক্লায়েন্ট তথ্য (ঐচ্ছিক)</div>
            <div class="d-flex" style="gap: 12px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">নাম</label>
                    <input type="text" name="client_name" placeholder="যেমন: রহিম মিয়া" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">মোবাইল</label>
                    <input type="text" name="client_phone" placeholder="যেমন: 017XXXXXXXX" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                </div>
            </div>
        </x-card>

        <x-card style="margin-top: 16px;">
            <div class="d-flex justify-between align-center mb-3">
                <div style="font-weight: 600; font-size: 16px;">আইটেমসমূহ</div>
            </div>
            
            <div id="itemsContainer"></div>

            <!-- Add Item Form -->
            <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; padding: 16px; border-radius: 12px; margin-top: 12px;">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">আইটেমের নাম</label>
                    <input type="text" id="itemName" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                </div>
                <div class="d-flex" style="gap: 12px; margin-bottom: 12px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">পরিমাণ</label>
                        <input type="number" id="itemQty" value="1" min="1" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">একক দাম</label>
                        <input type="number" id="itemPrice" min="0" style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                    </div>
                </div>
                <button type="button" onclick="addItem()" class="btn btn-primary" style="width: 100%; background: #3B82F6; padding: 10px;">+ আইটেম যোগ করুন</button>
            </div>
        </x-card>

        <div style="position: fixed; bottom: 80px; left: 0; width: 100%; padding: 16px; background: white; border-top: 1px solid #E2E8F0; box-shadow: 0 -4px 12px rgba(0,0,0,0.05); z-index: 100;">
            <div class="d-flex justify-between align-center mb-3">
                <div style="font-weight: 600; font-size: 16px;">সর্বমোট:</div>
                <div id="grandTotal" style="font-weight: 700; font-size: 22px; color: var(--primary);">0৳</div>
            </div>
            <button type="button" onclick="submitInvoice()" class="btn btn-primary" style="width: 100%; font-size: 16px; padding: 14px; border-radius: 12px; font-weight: 600;">ইনভয়েস সেভ করুন</button>
        </div>
        
        <!-- Padding at bottom so scroll doesn't hide behind fixed footer -->
        <div style="height: 120px;"></div>
    </form>
</div>

<script>
    let items = [];

    function updateView() {
        const container = document.getElementById('itemsContainer');
        container.innerHTML = '';
        
        let grandTotal = 0;
        
        items.forEach((item, index) => {
            const total = item.qty * item.price;
            grandTotal += total;
            
            container.innerHTML += `
                <div class="d-flex justify-between align-center mb-2" style="background: white; border: 1px solid #E2E8F0; padding: 12px; border-radius: 8px;">
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">${item.name}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">${item.qty} x ${item.price}৳</div>
                    </div>
                    <div class="d-flex align-center" style="gap: 12px;">
                        <div style="font-weight: 700; font-size: 15px;">${total}৳</div>
                        <button type="button" onclick="removeItem(${index})" style="background: transparent; border: none; color: var(--danger); font-size: 18px; cursor: pointer;">🗑️</button>
                    </div>
                </div>
            `;
        });
        
        document.getElementById('grandTotal').innerText = grandTotal + '৳';
        document.getElementById('itemsInput').value = JSON.stringify(items);
    }

    function addItem() {
        const nameInput = document.getElementById('itemName');
        const qtyInput = document.getElementById('itemQty');
        const priceInput = document.getElementById('itemPrice');
        
        if (!nameInput.value) return alert('আইটেমের নাম দিন');
        if (!priceInput.value) return alert('দাম দিন');
        
        items.push({
            name: nameInput.value,
            qty: parseInt(qtyInput.value) || 1,
            price: parseFloat(priceInput.value) || 0
        });
        
        nameInput.value = '';
        qtyInput.value = '1';
        priceInput.value = '';
        
        updateView();
    }

    function removeItem(index) {
        items.splice(index, 1);
        updateView();
    }
    
    function submitInvoice() {
        if (items.length === 0) {
            alert('কমপক্ষে একটি আইটেম যোগ করুন!');
            return;
        }
        document.getElementById('invoiceForm').submit();
    }
</script>
@endsection
