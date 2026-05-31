<style>
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 430px;
        background: var(--surface);
        box-shadow: 0 -2px 12px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        padding: 0 0 calc(8px + env(safe-area-inset-bottom)) 0;
        z-index: 100;
        height: 64px;
        border-top: 1px solid #F3E6ED;
    }
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        text-decoration: none;
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 500;
        padding: 8px 4px;
        min-width: 56px;
        gap: 3px;
        transition: color 0.2s;
    }
    .nav-item .icon {
        width: 26px;
        height: 26px;
        object-fit: contain;
        transition: transform 0.2s;
        filter: grayscale(30%) opacity(0.7);
    }
    .nav-item.active {
        color: #D42B6A;
        font-weight: 600;
    }
    .nav-item.active .icon {
        filter: none;
        transform: scale(1.1);
    }
    
    /* FAB middle button */
    .nav-fab-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        min-width: 72px;
        position: relative;
        padding-bottom: 6px;
        margin-top: -24px;  /* lifts above nav bar */
    }
    .nav-fab {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #9D1C5B 0%, #D42B6A 100%);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 16px rgba(157, 28, 91, 0.45);
        border: 3px solid var(--surface);
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
    }
    .nav-fab:active {
        transform: scale(0.93);
        box-shadow: 0 2px 8px rgba(157, 28, 91, 0.3);
    }
    .nav-fab-label {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-top: 3px;
        line-height: 1;
    }
    .nav-fab-label.active {
        color: #D42B6A;
        font-weight: 600;
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
        
        <div class="nav-fab-wrapper">
            <a href="/calculator" class="nav-fab">
                <img src="{{ asset('images/icons/accounting_icon_1780217086495.png') }}" style="width: 30px; height: 30px; object-fit: contain;">
            </a>
            <span class="nav-fab-label {{ request()->is('calculator') ? 'active' : '' }}">হিসাব</span>
        </div>

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
        
        <div class="nav-fab-wrapper">
            <a href="/calculator" class="nav-fab">
                <img src="{{ asset('images/icons/accounting_icon_1780217086495.png') }}" style="width: 30px; height: 30px; object-fit: contain;">
            </a>
            <span class="nav-fab-label {{ request()->is('calculator') ? 'active' : '' }}">হিসাব</span>
        </div>

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
