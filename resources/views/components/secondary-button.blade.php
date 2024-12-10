<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-secondary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-secondary-dark focus:bg-secondary-dark active:bg-secondary-darker transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>