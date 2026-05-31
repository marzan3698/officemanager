<style>
    .card {
        background: var(--surface);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        padding: 16px;
        margin-bottom: 16px;
    }
</style>
<div class="card {{ $attributes->get('class') }}">
    {{ $slot }}
</div>
