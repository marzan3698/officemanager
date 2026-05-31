<style>
    .stat-card {
        background: linear-gradient(135deg, var(--primary) 0%, #3B82F6 100%);
        color: white;
        border-radius: var(--radius-md);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow-sm);
        margin-bottom: 16px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }
    .stat-icon {
        font-size: 32px;
        opacity: 0.8;
    }
</style>
<div class="stat-card">
    <div>
        <div class="stat-value">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
    <div class="stat-icon">{{ $icon }}</div>
</div>
