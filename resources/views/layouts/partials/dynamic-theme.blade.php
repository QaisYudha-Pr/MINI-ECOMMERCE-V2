@php
    $themeColor = auth()->check() ? (auth()->user()->theme_color ?? 'indigo') : 'indigo';
    $colorMap = [
        'emerald' => ['50' => '#ecfdf5', '100' => '#d1fae5', '200' => '#a7f3d0', '300' => '#6ee7b7', '400' => '#34d399', '500' => '#10b981', '600' => '#059669', '700' => '#047857', '800' => '#065f46', '900' => '#064e3b'],
        'rose' => ['50' => '#fff1f2', '100' => '#ffe4e6', '200' => '#fecdd3', '300' => '#fda4af', '400' => '#fb7185', '500' => '#f43f5e', '600' => '#e11d48', '700' => '#be123c', '800' => '#9f1239', '900' => '#881337'],
        'amber' => ['50' => '#fffbeb', '100' => '#fef3c7', '200' => '#fde68a', '300' => '#fcd34d', '400' => '#fbbf24', '500' => '#f59e0b', '600' => '#d97706', '700' => '#b45309', '800' => '#92400e', '900' => '#78350f'],
        'slate' => ['50' => '#f8fafc', '100' => '#f1f5f9', '200' => '#e2e8f0', '300' => '#cbd5e1', '400' => '#94a3b8', '500' => '#64748b', '600' => '#475569', '700' => '#334155', '800' => '#1e293b', '900' => '#0f172a'],
        'indigo' => ['50' => '#eef2ff', '100' => '#e0e7ff', '200' => '#c7d2fe', '300' => '#a5b4fc', '400' => '#818cf8', '500' => '#6366f1', '600' => '#4f46e5', '700' => '#4338ca', '800' => '#3730a3', '900' => '#312e81'],
    ];

    // Support custom hex colors (e.g. #ff6b2b)
    if (str_starts_with($themeColor, '#') && strlen($themeColor) === 7) {
        $hex = $themeColor;
        $r = hexdec(substr($hex, 1, 2));
        $g = hexdec(substr($hex, 3, 2));
        $b = hexdec(substr($hex, 5, 2));
        // Generate shade palette from base hex by mixing with white (lighter) and black (darker)
        $mix = function($r, $g, $b, $r2, $g2, $b2, $w) {
            return sprintf('#%02x%02x%02x',
                (int)($r * $w + $r2 * (1 - $w)),
                (int)($g * $w + $g2 * (1 - $w)),
                (int)($b * $w + $b2 * (1 - $w))
            );
        };
        $c = [
            '50'  => $mix($r,$g,$b, 255,255,255, 0.05),
            '100' => $mix($r,$g,$b, 255,255,255, 0.15),
            '200' => $mix($r,$g,$b, 255,255,255, 0.30),
            '300' => $mix($r,$g,$b, 255,255,255, 0.50),
            '400' => $mix($r,$g,$b, 255,255,255, 0.70),
            '500' => $hex,
            '600' => $mix($r,$g,$b, 0,0,0, 0.85),
            '700' => $mix($r,$g,$b, 0,0,0, 0.70),
            '800' => $mix($r,$g,$b, 0,0,0, 0.55),
            '900' => $mix($r,$g,$b, 0,0,0, 0.40),
        ];
    } else {
        $c = $colorMap[$themeColor] ?? $colorMap['indigo'];
    }
@endphp

<style>
    :root {
        --brand-50: {{ $c['50'] }};
        --brand-100: {{ $c['100'] }};
        --brand-200: {{ $c['200'] }};
        --brand-400: {{ $c['400'] }};
        --brand-500: {{ $c['500'] }};
        --brand-600: {{ $c['600'] }};
        --brand-700: {{ $c['700'] }};
        --brand-900: {{ $c['900'] }};
    }

    /* Hijack Major Tailwind Color Classes for Dynamic Themes */
    .text-emerald-50, .text-indigo-50, .text-blue-50, .text-teal-50, .text-green-50, .text-rose-50, .text-amber-50, .text-violet-50, .text-purple-50, .text-fuchsia-50, .text-sky-50, .text-cyan-50, .text-orange-50 { color: var(--brand-50) !important; }
    .text-emerald-100, .text-indigo-100, .text-blue-100, .text-teal-100, .text-green-100, .text-rose-100, .text-amber-100, .text-violet-100, .text-purple-100, .text-fuchsia-100, .text-sky-100, .text-cyan-100, .text-orange-100 { color: var(--brand-100) !important; }
    .text-emerald-400, .text-indigo-400, .text-blue-400, .text-teal-400, .text-green-400, .text-rose-400, .text-amber-400, .text-violet-400, .text-purple-400, .text-fuchsia-400, .text-sky-400, .text-cyan-400, .text-orange-400 { color: var(--brand-400) !important; }
    .text-emerald-500, .text-indigo-500, .text-blue-500, .text-teal-500, .text-green-500, .text-rose-500, .text-amber-500, .text-violet-500, .text-purple-500, .text-fuchsia-500, .text-sky-500, .text-cyan-500, .text-orange-500 { color: var(--brand-500) !important; }
    .text-emerald-600, .text-indigo-600, .text-blue-600, .text-teal-600, .text-green-600, .text-rose-600, .text-amber-600, .text-violet-600, .text-purple-600, .text-fuchsia-600, .text-sky-600, .text-cyan-600, .text-orange-600 { color: var(--brand-600) !important; }
    .text-emerald-700, .text-indigo-700, .text-blue-700, .text-teal-700, .text-green-700, .text-rose-700, .text-amber-700, .text-violet-700, .text-purple-700, .text-fuchsia-700, .text-sky-700, .text-cyan-700, .text-orange-700 { color: var(--brand-700) !important; }
    .text-emerald-800, .text-indigo-800, .text-blue-800, .text-teal-800, .text-green-800, .text-rose-800, .text-amber-800, .text-violet-800, .text-purple-800, .text-fuchsia-800, .text-sky-800, .text-cyan-800, .text-orange-800 { color: var(--brand-800) !important; }
    .text-emerald-900, .text-indigo-900, .text-blue-900, .text-teal-900, .text-green-900, .text-rose-900, .text-amber-900, .text-violet-900, .text-purple-900, .text-fuchsia-900, .text-sky-900, .text-cyan-900, .text-orange-900 { color: var(--brand-900) !important; }
    
    .bg-emerald-50, .bg-indigo-50, .bg-blue-50, .bg-teal-50, .bg-green-50, .bg-rose-50, .bg-amber-50, .bg-violet-50, .bg-purple-50, .bg-fuchsia-50, .bg-sky-50, .bg-cyan-50, .bg-orange-50 { background-color: var(--brand-50) !important; }
    .bg-emerald-100, .bg-indigo-100, .bg-blue-100, .bg-teal-100, .bg-green-100, .bg-rose-100, .bg-amber-100, .bg-violet-100, .bg-purple-100, .bg-fuchsia-100, .bg-sky-100, .bg-cyan-100, .bg-orange-100 { background-color: var(--brand-100) !important; }
    .bg-emerald-200, .bg-indigo-200, .bg-blue-200, .bg-teal-200, .bg-green-200, .bg-rose-200, .bg-amber-200, .bg-violet-200, .bg-purple-200, .bg-fuchsia-200, .bg-sky-200, .bg-cyan-200, .bg-orange-200 { background-color: var(--brand-200) !important; }
    .bg-emerald-300, .bg-indigo-300, .bg-blue-300, .bg-teal-300, .bg-green-300, .bg-rose-300, .bg-amber-300, .bg-violet-300, .bg-purple-300, .bg-fuchsia-300, .bg-sky-300, .bg-cyan-300, .bg-orange-300 { background-color: var(--brand-300) !important; }
    .bg-emerald-400, .bg-indigo-400, .bg-blue-400, .bg-teal-400, .bg-green-400, .bg-rose-400, .bg-amber-400, .bg-violet-400, .bg-purple-400, .bg-fuchsia-400, .bg-sky-400, .bg-cyan-400, .bg-orange-400 { background-color: var(--brand-400) !important; }
    .bg-emerald-500, .bg-indigo-500, .bg-blue-500, .bg-teal-500, .bg-green-500, .bg-rose-500, .bg-amber-500, .bg-violet-500, .bg-purple-500, .bg-fuchsia-500, .bg-sky-500, .bg-cyan-500, .bg-orange-500 { background-color: var(--brand-500) !important; }
    .bg-emerald-600, .bg-indigo-600, .bg-blue-600, .bg-teal-600, .bg-green-600, .bg-rose-600, .bg-amber-600, .bg-violet-600, .bg-purple-600, .bg-fuchsia-600, .bg-sky-600, .bg-cyan-600, .bg-orange-600 { background-color: var(--brand-600) !important; }
    .bg-emerald-700, .bg-indigo-700, .bg-blue-700, .bg-teal-700, .bg-green-700, .bg-rose-700, .bg-amber-700, .bg-violet-700, .bg-purple-700, .bg-fuchsia-700, .bg-sky-700, .bg-cyan-700, .bg-orange-700 { background-color: var(--brand-700) !important; }
    .bg-emerald-800, .bg-indigo-800, .bg-blue-800, .bg-teal-800, .bg-green-800, .bg-rose-800, .bg-amber-800, .bg-violet-800, .bg-purple-800, .bg-fuchsia-800, .bg-sky-800, .bg-cyan-800, .bg-orange-800 { background-color: var(--brand-800) !important; }
    .bg-emerald-900, .bg-indigo-900, .bg-blue-900, .bg-teal-900, .bg-green-900, .bg-rose-900, .bg-amber-900, .bg-violet-900, .bg-purple-900, .bg-fuchsia-900, .bg-sky-900, .bg-cyan-900, .bg-orange-900 { background-color: var(--brand-900) !important; }
    
    .border-emerald-50, .border-indigo-50, .border-blue-50, .border-teal-50, .border-green-50, .border-rose-50, .border-amber-50, .border-violet-50, .border-purple-50, .border-fuchsia-50, .border-sky-50, .border-cyan-50, .border-orange-50 { border-color: var(--brand-50) !important; }
    .border-emerald-100, .border-indigo-100, .border-blue-100, .border-teal-100, .border-green-100, .border-rose-100, .border-amber-100, .border-violet-100, .border-purple-100, .border-fuchsia-100, .border-sky-100, .border-cyan-100, .border-orange-100 { border-color: var(--brand-100) !important; }
    .border-emerald-200, .border-indigo-200, .border-blue-200, .border-teal-200, .border-green-200, .border-rose-200, .border-amber-200, .border-violet-200, .border-purple-200, .border-fuchsia-200, .border-sky-200, .border-cyan-200, .border-orange-200 { border-color: var(--brand-200) !important; }
    .border-emerald-400, .border-indigo-400, .border-blue-400, .border-teal-400, .border-green-400, .border-rose-400, .border-amber-400, .border-violet-400, .border-purple-400, .border-fuchsia-400, .border-sky-400, .border-cyan-400, .border-orange-400 { border-color: var(--brand-400) !important; }
    .border-emerald-500, .border-indigo-500, .border-blue-500, .border-teal-500, .border-green-500, .border-rose-500, .border-amber-500, .border-violet-500, .border-purple-500, .border-fuchsia-500, .border-sky-500, .border-cyan-500, .border-orange-500 { border-color: var(--brand-500) !important; }
    .border-emerald-600, .border-indigo-600, .border-blue-600, .border-teal-600, .border-green-600, .border-rose-600, .border-amber-600, .border-violet-600, .border-purple-600, .border-fuchsia-600, .border-sky-600, .border-cyan-600, .border-orange-600 { border-color: var(--brand-600) !important; }
    
    .decoration-emerald-200, .decoration-indigo-200, .decoration-blue-200, .decoration-teal-200, .decoration-green-200 { text-decoration-color: var(--brand-200) !important; }
    .decoration-emerald-400, .decoration-indigo-400, .decoration-blue-400, .decoration-teal-400, .decoration-green-400 { text-decoration-color: var(--brand-400) !important; }
    .decoration-emerald-500, .decoration-indigo-500, .decoration-blue-500, .decoration-teal-500, .decoration-green-500 { text-decoration-color: var(--brand-500) !important; }
    .decoration-emerald-600, .decoration-indigo-600, .decoration-blue-600, .decoration-teal-600, .decoration-green-600 { text-decoration-color: var(--brand-600) !important; }

    .focus\:border-emerald-500:focus, .focus\:border-indigo-500:focus, .focus\:border-blue-500:focus, .focus\:border-teal-500:focus, .focus\:border-green-500:focus, .focus\:border-rose-500:focus, .focus\:border-amber-500:focus, .focus\:border-violet-500:focus, .focus\:border-purple-500:focus, .focus\:border-fuchsia-500:focus, .focus\:border-sky-500:focus, .focus\:border-cyan-500:focus, .focus\:border-orange-500:focus { border-color: var(--brand-500) !important; }
    .focus\:ring-emerald-500:focus, .focus\:ring-indigo-500:focus, .focus\:ring-blue-500:focus, .focus\:ring-teal-500:focus, .focus\:ring-green-500:focus, .focus\:ring-rose-500:focus, .focus\:ring-amber-500:focus, .focus\:ring-violet-500:focus, .focus\:ring-purple-500:focus, .focus\:ring-fuchsia-500:focus, .focus\:ring-sky-500:focus, .focus\:ring-cyan-500:focus, .focus\:ring-orange-500:focus { --tw-ring-color: var(--brand-500) !important; }
    
    .hover\:text-emerald-600:hover, .hover\:text-indigo-600:hover, .hover\:text-blue-600:hover, .hover\:text-teal-600:hover, .hover\:text-green-600:hover, .hover\:text-rose-600:hover, .hover\:text-amber-600:hover, .hover\:text-violet-600:hover, .hover\:text-purple-600:hover, .hover\:text-fuchsia-600:hover, .hover\:text-sky-600:hover, .hover\:text-cyan-600:hover, .hover\:text-orange-600:hover { color: var(--brand-600) !important; }
    .hover\:bg-emerald-50:hover, .hover\:bg-indigo-50:hover, .hover\:bg-blue-50:hover, .hover\:bg-teal-50:hover, .hover\:bg-green-50:hover, .hover\:bg-rose-50:hover, .hover\:bg-amber-50:hover, .hover\:bg-violet-50:hover, .hover\:bg-purple-50:hover, .hover\:bg-fuchsia-50:hover, .hover\:bg-sky-50:hover, .hover\:bg-cyan-50:hover, .hover\:bg-orange-50:hover { background-color: var(--brand-50) !important; }
    .hover\:bg-emerald-600:hover, .hover\:bg-indigo-600:hover, .hover\:bg-blue-600:hover, .hover\:bg-teal-600:hover, .hover\:bg-green-600:hover, .hover\:bg-rose-600:hover, .hover\:bg-amber-600:hover, .hover\:bg-violet-600:hover, .hover\:bg-purple-600:hover, .hover\:bg-fuchsia-600:hover, .hover\:bg-sky-600:hover, .hover\:bg-cyan-600:hover, .hover\:bg-orange-600:hover { background-color: var(--brand-600) !important; }
    .hover\:border-emerald-600:hover, .hover\:border-indigo-600:hover, .hover\:border-blue-600:hover, .hover\:border-teal-600:hover, .hover\:border-green-600:hover, .hover\:border-rose-600:hover, .hover\:border-amber-600:hover, .hover\:border-violet-600:hover, .hover\:border-purple-600:hover, .hover\:border-fuchsia-600:hover, .hover\:border-sky-600:hover, .hover\:border-cyan-600:hover, .hover\:border-orange-600:hover { border-color: var(--brand-600) !important; }
    
    .shadow-emerald-100, .shadow-indigo-100, .shadow-blue-100, .shadow-teal-100, .shadow-green-100, .shadow-rose-100, .shadow-amber-100, .shadow-violet-100, .shadow-purple-100, .shadow-fuchsia-100, .shadow-sky-100, .shadow-cyan-100, .shadow-orange-100 { --tw-shadow-color: var(--brand-100) !important; }
    .shadow-emerald-200, .shadow-indigo-200, .shadow-blue-200, .shadow-teal-200, .shadow-green-200, .shadow-rose-200, .shadow-amber-200, .shadow-violet-200, .shadow-purple-200, .shadow-fuchsia-200, .shadow-sky-200, .shadow-cyan-200, .shadow-orange-200 { --tw-shadow-color: var(--brand-200) !important; }
    .shadow-emerald-500, .shadow-indigo-500, .shadow-blue-500, .shadow-teal-500, .shadow-green-500, .shadow-rose-500, .shadow-amber-500, .shadow-violet-500, .shadow-purple-500, .shadow-fuchsia-500, .shadow-sky-500, .shadow-cyan-500, .shadow-orange-500 { --tw-shadow-color: var(--brand-500) !important; }
    
    .accent-emerald-600, .accent-indigo-600, .accent-blue-600, .accent-teal-600, .accent-green-600, .accent-rose-600, .accent-amber-600, .accent-violet-600, .accent-purple-600, .accent-fuchsia-600, .accent-sky-600, .accent-cyan-600, .accent-orange-600 { accent-color: var(--brand-600) !important; }

    .ring-emerald-50, .ring-indigo-50, .ring-blue-50, .ring-teal-50, .ring-green-50, .ring-rose-50, .ring-amber-50, .ring-violet-50, .ring-purple-50, .ring-fuchsia-50, .ring-sky-50, .ring-cyan-50, .ring-orange-50 { --tw-ring-color: var(--brand-50) !important; }
    .ring-emerald-100, .ring-indigo-100, .ring-blue-100, .ring-teal-100, .ring-green-100, .ring-rose-100, .ring-amber-100, .ring-violet-100, .ring-purple-100, .ring-fuchsia-100, .ring-sky-100, .ring-cyan-100, .ring-orange-100 { --tw-ring-color: var(--brand-100) !important; }
    .ring-emerald-500, .ring-indigo-500, .ring-blue-500, .ring-teal-500, .ring-green-500, .ring-rose-500, .ring-amber-500, .ring-violet-500, .ring-purple-500, .ring-fuchsia-500, .ring-sky-500, .ring-cyan-500, .ring-orange-500 { --tw-ring-color: var(--brand-500) !important; }

    /* Gradients Support */
    [class*="from-emerald"], [class*="from-indigo"], [class*="from-blue"], [class*="from-teal"], [class*="from-green"], [class*="from-rose"], [class*="from-amber"], [class*="from-violet"], [class*="from-purple"], [class*="from-fuchsia"], [class*="from-sky"], [class*="from-cyan"], [class*="from-orange"] { --tw-gradient-to: rgb(255 255 255 / 0) !important; }
    
    .from-emerald-400, .from-indigo-400, .from-blue-400, .from-teal-400, .from-green-400, .from-rose-400, .from-amber-400, .from-violet-400, .from-purple-400, .from-fuchsia-400, .from-sky-400, .from-cyan-400, .from-orange-400 { --tw-gradient-from: var(--brand-400) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-emerald-300, .from-indigo-300, .from-blue-300, .from-teal-300, .from-green-300, .from-rose-300, .from-amber-300, .from-violet-300, .from-purple-300, .from-fuchsia-300, .from-sky-300, .from-cyan-300, .from-orange-300 { --tw-gradient-from: var(--brand-300) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-emerald-500, .from-indigo-500, .from-blue-500, .from-teal-500, .from-green-500, .from-rose-500, .from-amber-500, .from-violet-500, .from-purple-500, .from-fuchsia-500, .from-sky-500, .from-cyan-500, .from-orange-500 { --tw-gradient-from: var(--brand-500) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    .from-emerald-600, .from-indigo-600, .from-blue-600, .from-teal-600, .from-green-600, .from-rose-600, .from-amber-600, .from-violet-600, .from-purple-600, .from-fuchsia-600, .from-sky-600, .from-cyan-600, .from-orange-600 { --tw-gradient-from: var(--brand-600) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    
    .via-emerald-400, .via-indigo-400, .via-blue-400, .via-teal-400, .via-green-400, .via-rose-400, .via-amber-400, .via-violet-400, .via-purple-400, .via-fuchsia-400, .via-sky-400, .via-cyan-400, .via-orange-400 { --tw-gradient-stops: var(--tw-gradient-from), var(--brand-400), var(--tw-gradient-to) !important; }
    .via-emerald-500, .via-indigo-500, .via-blue-500, .via-teal-500, .via-green-500, .via-rose-500, .via-amber-500, .via-violet-500, .via-purple-500, .via-fuchsia-500, .via-sky-500, .via-cyan-500, .via-orange-500 { --tw-gradient-stops: var(--tw-gradient-from), var(--brand-500), var(--tw-gradient-to) !important; }
    
    .to-emerald-400, .to-indigo-400, .to-blue-400, .to-teal-400, .to-green-400, .to-rose-400, .to-amber-400, .to-violet-400, .to-purple-400, .to-fuchsia-400, .to-sky-400, .to-cyan-400, .to-orange-400 { --tw-gradient-to: var(--brand-400) !important; }
    .to-emerald-500, .to-indigo-500, .to-blue-500, .to-teal-500, .to-green-500, .to-rose-500, .to-amber-500, .to-violet-500, .to-purple-500, .to-fuchsia-500, .to-sky-500, .to-cyan-500, .to-orange-500 { --tw-gradient-to: var(--brand-500) !important; }
    .to-emerald-600, .to-indigo-600, .to-blue-600, .to-teal-600, .to-green-600, .to-rose-600, .to-amber-600, .to-violet-600, .to-purple-600, .to-fuchsia-600, .to-sky-600, .to-cyan-600, .to-orange-600 { --tw-gradient-to: var(--brand-600) !important; }
    .to-emerald-700, .to-indigo-700, .to-blue-700, .to-teal-700, .to-green-700, .to-rose-700, .to-amber-700, .to-violet-700, .to-purple-700, .to-fuchsia-700, .to-sky-700, .to-cyan-700, .to-orange-700 { --tw-gradient-to: var(--brand-700) !important; }
    .to-emerald-800, .to-indigo-800, .to-blue-800, .to-teal-800, .to-green-800, .to-rose-800, .to-amber-800, .to-violet-800, .to-purple-800, .to-fuchsia-800, .to-sky-800, .to-cyan-800, .to-orange-800 { --tw-gradient-to: var(--brand-800) !important; }

    /* Themed Sidebar */
    aside.themed-sidebar {
        background: linear-gradient(180deg, var(--brand-50) 0%, white 100%) !important;
        border-right-color: var(--brand-100) !important;
    }
    aside.themed-sidebar .sidebar-section-label {
        color: var(--brand-600) !important;
    }
    aside.themed-sidebar a.sidebar-active {
        background-color: var(--brand-100) !important;
        color: var(--brand-700) !important;
    }
    aside.themed-sidebar a.sidebar-active svg {
        color: var(--brand-700) !important;
    }
    aside.themed-sidebar a:not(.sidebar-active):hover {
        background-color: var(--brand-50) !important;
    }
    .border-emerald-100 { border-color: var(--brand-100) !important; }
</style>


