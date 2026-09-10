{{--
    Proposal · the drawn ring.

    Every design offers a `ring_photo` and falls back to this when there isn't
    one, so a page with no uploads at all still has a ring in it. It inherits
    the surrounding theme through the `--metal1` / `--metal2` custom properties
    the designs already define, so it is never the wrong colour for the page —
    and the stone carries its own outline, because the light themes would
    otherwise print a white diamond on a cream card.
--}}
<svg viewBox="0 0 120 120" role="img" aria-label="An engagement ring" focusable="false">
    <defs>
        <linearGradient id="prBand" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="var(--metal1, #e6c19c)" />
            <stop offset="1" stop-color="var(--metal2, #c98f6d)" />
        </linearGradient>
        <linearGradient id="prStone" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#ffffff" />
            <stop offset=".45" stop-color="#dcefff" />
            <stop offset="1" stop-color="#8fb8d8" />
        </linearGradient>
    </defs>

    <!-- the band -->
    <circle cx="60" cy="80" r="26" fill="none" stroke="url(#prBand)" stroke-width="9" />
    <circle cx="60" cy="80" r="26" fill="none" stroke="rgba(255,255,255,.4)" stroke-width="2.5" />

    <!-- the setting the stone sits in -->
    <path d="M49 52 L60 60 L71 52" fill="none" stroke="url(#prBand)" stroke-width="5" stroke-linecap="round"
        stroke-linejoin="round" />

    <!-- a brilliant cut: crown, table and pavilion, outlined so it reads on a
         pale background as well as on a dark one -->
    <g stroke="var(--metal2, #c98f6d)" stroke-width="1.6" stroke-linejoin="round">
        <path d="M42 42 L60 26 L78 42 L60 62 Z" fill="url(#prStone)" />
        <path d="M42 42 L78 42" fill="none" />
        <path d="M51 34 L57 42 L60 62" fill="none" opacity=".7" />
        <path d="M69 34 L63 42 L60 62" fill="none" opacity=".7" />
    </g>

    <!-- one glint, off to the side rather than sitting on top of the stone -->
    <path d="M92 30 L94.4 38 L102 40.4 L94.4 42.8 L92 50.8 L89.6 42.8 L82 40.4 L89.6 38 Z"
        fill="#ffffff" opacity=".85" />
</svg>
