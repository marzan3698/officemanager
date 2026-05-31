@extends('layouts.app')

@section('content')
<style>
    .pos-header {
        background: linear-gradient(135deg, #1A56DB 0%, #3F83F8 100%);
        color: white;
        padding: 20px;
        border-radius: 0 0 20px 20px;
    }
    .pos-total-bar {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 12px 16px;
        border-radius: 12px;
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .material-input {
        position: relative;
        margin-bottom: 16px;
    }
    .material-input input, .material-input textarea {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        font-size: 15px;
        background: white;
        transition: border-color 0.2s;
        margin-bottom: 0;
    }
    .material-input input:focus, .material-input textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
    }
    .material-input label {
        position: absolute;
        top: -8px;
        left: 12px;
        background: white;
        padding: 0 6px;
        font-size: 11px;
        color: var(--primary);
        font-weight: 600;
    }
    .item-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideIn 0.2s ease-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .item-card .item-info {
        flex: 1;
    }
    .item-card .item-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
    }
    .item-card .item-detail {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 2px;
    }
    .item-card .item-total {
        font-weight: 700;
        font-size: 15px;
        color: var(--primary);
        margin-right: 8px;
    }
    .item-card .item-delete {
        width: 32px;
        height: 32px;
        border: none;
        background: #FDE8E8;
        color: var(--danger);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .item-card .item-delete:active {
        background: #FECACA;
    }
    .add-item-section {
        background: #F9FAFB;
        border: 2px dashed #D1D5DB;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .add-btn {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: opacity 0.2s;
    }
    .add-btn:active { opacity: 0.85; }
    .submit-fab {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 32px);
        max-width: 398px;
        background: linear-gradient(135deg, #0E9F6E 0%, #31C48D 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 16px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(14, 159, 110, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        z-index: 50;
        transition: transform 0.2s;
    }
    .submit-fab:active { transform: translateX(-50%) scale(0.98); }
    .submit-fab:disabled {
        background: #D1D5DB;
        box-shadow: none;
        cursor: not-allowed;
    }
    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: var(--text-secondary);
    }
    .empty-state .icon {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    .chip {
        display: inline-flex;
        align-items: center;
        background: #E1EFFE;
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        gap: 4px;
    }
    .section-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>

<!-- POS Header -->
<div class="pos-header">
    <div class="d-flex align-center" style="margin-bottom: 8px;">
        <a href="/employee/expenses" style="margin-right: 12px; font-size: 20px; color: white; text-decoration: none;">⬅</a>
        <div>
            <div style="font-size: 18px; font-weight: 700;">নতুন ইনভয়েস তৈরি করুন</div>
            <div style="font-size: 12px; opacity: 0.8;">আইটেম যুক্ত করে সাবমিট করুন</div>
        </div>
    </div>
    <div class="pos-total-bar">
        <div>
            <div style="font-size: 11px; opacity: 0.8;">মোট পরিমাণ</div>
            <div style="font-size: 24px; font-weight: 700;" id="grand-total">০৳</div>
        </div>
        <div>
            <div class="chip" id="item-count">🧾 ০ আইটেম</div>
        </div>
    </div>
</div>

<div class="content" style="padding: 16px; padding-bottom: 120px;">
    @if($errors->any())
        <div class="alert alert-error mb-4">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Items List -->
    <div class="section-label">📦 আইটেম তালিকা</div>
    <div id="items-list">
        <div class="empty-state" id="empty-msg">
            <div class="icon">🧾</div>
            <div style="font-size: 14px; font-weight: 500;">কোনো আইটেম নেই</div>
            <div style="font-size: 12px; margin-top: 4px;">নিচে থেকে আইটেম যুক্ত করুন</div>
        </div>
    </div>

    <!-- Add Item Section -->
    <div class="add-item-section">
        <div class="section-label">➕ নতুন আইটেম যুক্ত করুন</div>
        
        <div class="material-input">
            <label>আইটেমের নাম</label>
            <input type="text" id="inp-name" placeholder="যেমন: যাতায়াত ভাড়া">
        </div>
        
        <div class="d-flex" style="gap: 8px;">
            <div class="material-input" style="flex: 1;">
                <label>পরিমাণ</label>
                <input type="number" id="inp-qty" value="1" min="1">
            </div>
            <div class="material-input" style="flex: 1.5;">
                <label>দাম (৳)</label>
                <input type="number" id="inp-price" placeholder="০" min="0">
            </div>
        </div>
        
        <button type="button" class="add-btn" onclick="addItem()">
            ➕ আইটেম যুক্ত করুন
        </button>
    </div>

    <!-- Invoice Info -->
    <div class="section-label" style="margin-top: 16px;">📋 ইনভয়েসের তথ্য</div>
    <div style="background: white; border: 1px solid #E5E7EB; border-radius: 16px; padding: 16px;">
        <div class="material-input">
            <label>ইনভয়েস শিরোনাম</label>
            <input type="text" id="inp-title" placeholder="যেমন: মে মাসের খরচ">
        </div>
        
        <div class="material-input">
            <label>নোট (ঐচ্ছিক)</label>
            <textarea id="inp-note" rows="2" placeholder="অতিরিক্ত তথ্য..."></textarea>
        </div>
        
        <div class="material-input">
            <label>রশিদ / ডকুমেন্ট (ঐচ্ছিক)</label>
            <input type="file" id="inp-file" accept=".pdf,image/*" style="padding: 10px;">
        </div>
    </div>
</div>

<!-- Submit FAB -->
<button class="submit-fab" id="submit-btn" disabled onclick="submitInvoice()">
    📤 ইনভয়েস সাবমিট করুন
</button>

<!-- Hidden Form -->
<form id="invoice-form" method="POST" action="/employee/expenses" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="hidden" name="title" id="form-title">
    <input type="hidden" name="items" id="form-items">
    <input type="hidden" name="amount" id="form-amount">
    <input type="hidden" name="note" id="form-note">
</form>

<script>
    let items = [];
    
    function addItem() {
        const name = document.getElementById('inp-name').value.trim();
        const qty = parseInt(document.getElementById('inp-qty').value) || 0;
        const price = parseFloat(document.getElementById('inp-price').value) || 0;
        
        if (!name) { alert('আইটেমের নাম লিখুন'); return; }
        if (qty < 1) { alert('পরিমাণ কমপক্ষে ১ হতে হবে'); return; }
        if (price <= 0) { alert('দাম ০ এর বেশি হতে হবে'); return; }
        
        items.push({ name, qty, price });
        
        // Clear inputs
        document.getElementById('inp-name').value = '';
        document.getElementById('inp-qty').value = '1';
        document.getElementById('inp-price').value = '';
        document.getElementById('inp-name').focus();
        
        renderItems();
    }
    
    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
    }
    
    function renderItems() {
        const list = document.getElementById('items-list');
        const emptyMsg = document.getElementById('empty-msg');
        
        if (items.length === 0) {
            list.innerHTML = `<div class="empty-state" id="empty-msg">
                <div class="icon">🧾</div>
                <div style="font-size: 14px; font-weight: 500;">কোনো আইটেম নেই</div>
                <div style="font-size: 12px; margin-top: 4px;">নিচে থেকে আইটেম যুক্ত করুন</div>
            </div>`;
            document.getElementById('submit-btn').disabled = true;
        } else {
            let html = '';
            items.forEach((item, i) => {
                const total = item.qty * item.price;
                html += `<div class="item-card">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-detail">${item.qty} × ${item.price.toLocaleString()}৳</div>
                    </div>
                    <div class="item-total">${total.toLocaleString()}৳</div>
                    <button type="button" class="item-delete" onclick="removeItem(${i})">✕</button>
                </div>`;
            });
            list.innerHTML = html;
            document.getElementById('submit-btn').disabled = false;
        }
        
        // Update totals
        const grandTotal = items.reduce((sum, item) => sum + (item.qty * item.price), 0);
        document.getElementById('grand-total').textContent = grandTotal.toLocaleString() + '৳';
        document.getElementById('item-count').innerHTML = '🧾 ' + items.length + ' আইটেম';
    }
    
    function submitInvoice() {
        const title = document.getElementById('inp-title').value.trim();
        if (!title) { alert('ইনভয়েসের শিরোনাম লিখুন'); return; }
        if (items.length === 0) { alert('কমপক্ষে একটি আইটেম যুক্ত করুন'); return; }
        
        const grandTotal = items.reduce((sum, item) => sum + (item.qty * item.price), 0);
        
        document.getElementById('form-title').value = title;
        document.getElementById('form-items').value = JSON.stringify(items);
        document.getElementById('form-amount').value = grandTotal;
        document.getElementById('form-note').value = document.getElementById('inp-note').value;
        
        // Handle file
        const fileInput = document.getElementById('inp-file');
        if (fileInput.files.length > 0) {
            const dt = new DataTransfer();
            dt.items.add(fileInput.files[0]);
            const hiddenFile = document.createElement('input');
            hiddenFile.type = 'file';
            hiddenFile.name = 'invoice_file';
            hiddenFile.files = dt.files;
            hiddenFile.style.display = 'none';
            document.getElementById('invoice-form').appendChild(hiddenFile);
        }
        
        document.getElementById('invoice-form').submit();
    }
    
    // Allow Enter key to add item from price field
    document.getElementById('inp-price').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); addItem(); }
    });
    document.getElementById('inp-name').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('inp-price').focus(); }
    });
</script>
@endsection
