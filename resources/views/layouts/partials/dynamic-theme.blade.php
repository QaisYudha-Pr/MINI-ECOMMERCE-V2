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

    /* Hijack Emerald Classes for Dynamic Themes */
    .text-emerald-400 { color: var(--brand-400) !important; }
    .text-emerald-500 { color: var(--brand-500) !important; }
    .text-emerald-600 { color: var(--brand-600) !important; }
    .text-emerald-700 { color: var(--brand-700) !important; }
    
    .bg-emerald-50 { background-color: var(--brand-50) !important; }
    .bg-emerald-100 { background-color: var(--brand-100) !important; }
    .bg-emerald-200 { background-color: var(--brand-200) !important; }
    .bg-emerald-400 { background-color: var(--brand-400) !important; }
    .bg-emerald-500 { background-color: var(--brand-500) !important; }
    .bg-emerald-600 { background-color: var(--brand-600) !important; }
    .bg-emerald-700 { background-color: var(--brand-700) !important; }
    
    .border-emerald-200 { border-color: var(--brand-200) !important; }
    .border-emerald-400 { border-color: var(--brand-400) !important; }
    .border-emerald-500 { border-color: var(--brand-500) !important; }
    .border-emerald-600 { border-color: var(--brand-600) !important; }
    
    .decoration-emerald-200 { text-decoration-color: var(--brand-200) !important; }
    .decoration-emerald-400 { text-decoration-color: var(--brand-400) !important; }
    .decoration-emerald-500 { text-decoration-color: var(--brand-500) !important; }
    .decoration-emerald-600 { text-decoration-color: var(--brand-600) !important; }

    .focus\:border-emerald-500:focus { border-color: var(--brand-500) !important; }
    .focus\:ring-emerald-500:focus { --tw-ring-color: var(--brand-500) !important; }
    
    .hover\:text-emerald-600:hover { color: var(--brand-600) !important; }
    .hover\:bg-emerald-50:hover { background-color: var(--brand-50) !important; }
    .hover\:bg-emerald-600:hover { background-color: var(--brand-600) !important; }
    .hover\:border-emerald-600:hover { border-color: var(--brand-600) !important; }
    
    .shadow-emerald-100 { --tw-shadow-color: var(--brand-100) !important; }
    .shadow-emerald-200 { --tw-shadow-color: var(--brand-200) !important; }
    .shadow-emerald-500 { --tw-shadow-color: var(--brand-500) !important; }
    
    .accent-emerald-600 { accent-color: var(--brand-600) !important; }

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


