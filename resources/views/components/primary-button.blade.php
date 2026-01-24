<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-[10px] text-white uppercase tracking-[0.2em] hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300']) }}>
    {{ $slot }}
</button>
