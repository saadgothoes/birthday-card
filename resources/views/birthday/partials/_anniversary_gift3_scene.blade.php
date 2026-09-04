{{--
    Drawn fallback scene for anniversary gift 3 (pop-up book), used when a
    spread has no uploaded photo. Colours come from the CSS custom properties
    the parent partial already set (--paper-2, --scene, --heart, --accent).
    $kind: meet | trip | letter
--}}
@php $kind = $kind ?? 'meet'; @endphp
<svg viewBox="0 0 120 140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
    <rect width="120" height="140" fill="var(--paper-2)" />

    @if($kind === 'trip')
        <path d="M0 140 L34 78 L64 140 Z" fill="var(--scene)" opacity="0.5" />
        <path d="M44 140 L84 62 L120 140 Z" fill="var(--accent)" opacity="0.32" />
        <circle cx="98" cy="30" r="11" fill="var(--accent)" opacity="0.3" />
    @elseif($kind === 'letter')
        <rect x="26" y="40" width="68" height="52" rx="3" fill="var(--paper)" stroke="var(--accent)" stroke-width="2" />
        <path d="M26 44 L60 70 L94 44" fill="none" stroke="var(--accent)" stroke-width="2" />
        <path d="M54 74 q6 -9 12 0 q6 9 -12 18 q-18 -9 -12 -18 q6 -9 12 0z" fill="var(--heart)" />
    @else
        <g stroke="var(--accent)" stroke-width="2" opacity="0.5">
            <path d="M22 26 l5 10 M34 20 l0 12 M14 40 l12 3" />
        </g>
        <circle cx="96" cy="28" r="12" fill="var(--accent)" opacity="0.28" />
    @endif

    @if($kind !== 'letter')
        <rect y="96" width="120" height="44" fill="var(--scene)" opacity="0.55" />
        <g transform="translate(60,90)" fill="var(--scene)">
            <circle cx="-9" cy="-16" r="7" />
            <path d="M-20 -2 Q-9 -12 2 -2 L5 44 L-24 44 Z" />
            <circle cx="11" cy="-18" r="7" />
            <path d="M0 -4 Q11 -14 22 -4 L26 44 L-2 44 Z" />
        </g>
        <path d="M60 40 q5 -6.5 10 0 q5 6.5 -10 15 q-15 -8.5 -10 -15 q5 -6.5 10 0 z" fill="var(--heart)" opacity="0.85" />
    @endif
</svg>
