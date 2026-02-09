<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-emerald-600 border border-transparent rounded-xl font-semibold text-xs text-white tracking-wide hover:bg-emerald-700 hover:shadow-md hover:shadow-emerald-100 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300 cursor-pointer']) }}>
    {{ $slot }}
</button>

