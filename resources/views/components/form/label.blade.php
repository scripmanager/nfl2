@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-nfl-secondary">*</span>
    @endif
</label>