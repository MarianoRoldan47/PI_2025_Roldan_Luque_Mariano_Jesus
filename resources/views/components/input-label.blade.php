@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'form-label text-white']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
