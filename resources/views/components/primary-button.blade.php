<button {{ $attributes->merge([
    'class' => '
        inline-flex items-center px-4 py-2
        bg-[#22a7e1] text-white
        hover:bg-[#1b91c6]
        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#22a7e1]
        rounded text-sm font-medium transition
    '
]) }}>
    {{ $slot }}
</button>
