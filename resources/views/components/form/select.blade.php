@props([
    'name',
    'options',
    'label' => false,
    'selected' => false,
])

@if ($label)
<label for="">{{ $label }}</label>
@endif

<select 
    name="{{ $name }}" 
    {{ $attributes->class([
        'form-control',
        'form-select',
        'is-invalid' => $errors->has($name),
    ]) }}
>
@foreach ($options as $value => $text)
    <option value="{{ $value }}" @selected($value == $selected)>{{ $text }}</option>
@endforeach
</select>

@error ($name)
    <span class="invalid-feedback">
        {{ $message }}
    </span>
@enderror