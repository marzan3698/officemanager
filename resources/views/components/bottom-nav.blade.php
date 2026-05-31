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
        font-size: 20px;
    }
    .nav-item.active {
        color: var(--primary);
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
        background: linear-gradient(135deg, var(--primary) 0%, #3F83F8 100%);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        color: white;
        box-shadow: 0 4px 12px rgba(26, 86, 219, 0.4);
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
            <span class="icon">🏠</span>
            <span>হোম</span>
        </a>
        <a href="/admin/employees" class="nav-item {{ request()->is('admin/employees*') ? 'active' : '' }}">
            <span class="icon">👥</span>
            <span>কর্মীরা</span>
        </a>
        
        <a href="/calculator" class="nav-fab-container">
            <div class="nav-fab">🧮</div>
            <div class="nav-fab-label" style="{{ request()->is('calculator') ? 'color: var(--primary);' : '' }}">হিসাব</div>
        </a>

        <a href="/admin/transactions" class="nav-item {{ request()->is('admin/transactions*') ? 'active' : '' }}">
            <span class="icon">💰</span>
            <span>লেনদেন</span>
        </a>
        <a href="/admin/invoices" class="nav-item {{ request()->is('admin/invoices*') ? 'active' : '' }}">
            <span class="icon">🧾</span>
            <span>ইনভয়েস</span>
        </a>
    @else
        <a href="/employee/dashboard" class="nav-item {{ request()->is('employee/dashboard') ? 'active' : '' }}">
            <span class="icon">🏠</span>
            <span>হোম</span>
        </a>
        <a href="/employee/transactions" class="nav-item {{ request()->is('employee/transactions*') ? 'active' : '' }}">
            <span class="icon">💰</span>
            <span>লেনদেন</span>
        </a>
        
        <a href="/calculator" class="nav-fab-container">
            <div class="nav-fab">🧮</div>
            <div class="nav-fab-label" style="{{ request()->is('calculator') ? 'color: var(--primary);' : '' }}">হিসাব</div>
        </a>

        <a href="/employee/invoices" class="nav-item {{ request()->is('employee/invoices*') ? 'active' : '' }}">
            <span class="icon">🧾</span>
            <span>ইনভয়েস</span>
        </a>
        <a href="/employee/profile" class="nav-item {{ request()->is('employee/profile') ? 'active' : '' }}">
            <span class="icon">👤</span>
            <span>প্রোফাইল</span>
        </a>
    @endif
</div>
