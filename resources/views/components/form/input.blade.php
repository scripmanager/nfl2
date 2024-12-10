@props(['disabled' => false, 'error' => null])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-nfl-primary focus:ring focus:ring-nfl-primary focus:ring-opacity-50' . 
    ($error ? ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : '')
]) !!}>