@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => 'w-full px-3 py-2 bg-[#22a7e1] text-white border border-[#22a7e1] rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-white focus:border-white disabled:opacity-50 disabled:cursor-not-allowed transition'
    ]) }}
/>
