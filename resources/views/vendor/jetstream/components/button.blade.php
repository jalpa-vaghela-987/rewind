<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-full font-extrabold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary focus:ring focus:ring-primary-light disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
