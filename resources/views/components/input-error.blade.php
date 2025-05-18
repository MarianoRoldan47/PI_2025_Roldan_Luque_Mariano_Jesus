@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>
        @foreach ((array) $messages as $message)
            <div><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
        @endforeach
    </div>
@endif
