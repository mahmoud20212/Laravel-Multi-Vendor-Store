@props([
    'name',
    'options',
    'label' => false,
    'checked' => false,
])

@if ($label)
    <label for="">{{ $label }}</label>
@endif

@foreach ($options as $value => $text)
    <div class="form-check">
        <input
            {{ $attributes->class([
                'form-check-input',
                'is-invalid' => $errors->has($name)
            ]) }}
            type="radio"
            id="{{ $value }}"
            name="{{ $name }}"
            value="{{ $value }}"
            @checked(old($name, $checked) == $value)>
        <label for="{{ $value }}" class="form-check-label">
            {{ $text }}
        </label>
    </div>
@endforeach