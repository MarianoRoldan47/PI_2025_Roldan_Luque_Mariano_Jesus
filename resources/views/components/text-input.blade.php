@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'form-control bg-dark text-white border-secondary ' .
        ($errors->has($attributes->get('name')) ? 'is-invalid' : ''),
]) !!}>
