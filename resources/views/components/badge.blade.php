<style>
    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 600;
    }
    .badge-success { background: #DEF7EC; color: var(--success); }
    .badge-warning { background: #FEECDC; color: var(--warning); }
    .badge-danger { background: #FDE8E8; color: var(--danger); }
    .badge-primary { background: #E1EFFE; color: var(--primary); }
</style>
<span class="badge badge-{{ $type ?? 'primary' }}">
    {{ $slot }}
</span>
