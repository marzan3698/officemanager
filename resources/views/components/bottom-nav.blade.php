<style>
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 430px;
        background: var(--surface);
        box-shadow: 0 -1px 3px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-around;
        padding: 12px 0 calc(12px + env(safe-area-inset-bottom));
        z-index: 100;
    }
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: var(--text-secondary);
        font-size: 12px;
        gap: 4px;
    }
    .nav-item .icon {
        width: 24px;
        height: 24px;
        margin-bottom: 2px;
        object-fit: contain;
    }
    .nav-item.active {
        color: #D42B6A;
        font-weight: 600;
    }
    
    .nav-fab-container {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        top: -20px;
    }
    .nav-fab {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #9D1C5B 0%, #D42B6A 100%);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 12px rgba(212, 43, 106, 0.4);
        border: 4px solid var(--surface);
        margin-bottom: 4px;
        transition: transform 0.2s;
    }
    .nav-fab:active {
        transform: scale(0.95);
    }
    .nav-fab-label {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 500;
        margin-top: -8px;
    }
</style>
<div class="bottom-nav">
    @if(Auth::user()->role === 'admin')
        <a href="/admin/dashboard" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/home_icon_1780217068884.png') }}" class="icon">
            <span>হোম</span>
        </a>
        <a href="/admin/employees" class="nav-item {{ request()->is('admin/employees*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/employee_icon_1780216702010.png') }}" class="icon">
            <span>কর্মীরা</span>
        </a>
        
        <a href="/calculator" class="nav-fab-container">
            <div class="nav-fab"><img src="{{ asset('images/icons/accounting_icon_1780217086495.png') }}" style="width: 32px; height: 32px; object-fit: contain;"></div>
            <div class="nav-fab-label" style="{{ request()->is('calculator') ? 'color: #D42B6A; font-weight: 600;' : '' }}">হিসাব</div>
        </a>

        <a href="/admin/transactions" class="nav-item {{ request()->is('admin/transactions*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/transaction_icon_1780216715077.png') }}" class="icon">
            <span>লেনদেন</span>
        </a>
        <a href="/admin/invoices" class="nav-item {{ request()->is('admin/invoices*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/invoice_icon_1780216787465.png') }}" class="icon">
            <span>ইনভয়েস</span>
        </a>
    @else
        <a href="/employee/dashboard" class="nav-item {{ request()->is('employee/dashboard') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/home_icon_1780217068884.png') }}" class="icon">
            <span>হোম</span>
        </a>
        <a href="/employee/transactions" class="nav-item {{ request()->is('employee/transactions*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/transaction_icon_1780216715077.png') }}" class="icon">
            <span>লেনদেন</span>
        </a>
        
        <a href="/calculator" class="nav-fab-container">
            <div class="nav-fab"><img src="{{ asset('images/icons/accounting_icon_1780217086495.png') }}" style="width: 32px; height: 32px; object-fit: contain;"></div>
            <div class="nav-fab-label" style="{{ request()->is('calculator') ? 'color: #D42B6A; font-weight: 600;' : '' }}">হিসাব</div>
        </a>

        <a href="/employee/invoices" class="nav-item {{ request()->is('employee/invoices*') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/invoice_icon_1780216787465.png') }}" class="icon">
            <span>ইনভয়েস</span>
        </a>
        <a href="/employee/profile" class="nav-item {{ request()->is('employee/profile') ? 'active' : '' }}">
            <img src="{{ asset('images/icons/employee_icon_1780216702010.png') }}" class="icon">
            <span>প্রোফাইল</span>
        </a>
    @endif
</div>
