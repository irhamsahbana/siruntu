@props([
    'col' => 12,

    'label' => null,
    'id' => $id ? $id : $name,
    'name' => null,
    'value' => null,
    'isChecked' => false,
])

<div class="form-check">
    <input
        class="form-check-input"
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $isChecked ? 'checked' : '' }}>
    <label class="form-check-label">{{ $label }}</label>
</div>
