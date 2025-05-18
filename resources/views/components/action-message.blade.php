@props(['on'])

<div x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
    x-show="shown"
    style="display: none;"
    class="alert alert-success d-flex align-items-center" role="alert">
    {{ $slot }}
</div>
