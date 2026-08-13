<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warm Amber — Together Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg-1: #3a2a1a;
        --bg-2: #1a1008;
        --accent: #c8935a;
        --btn-bg: #f0d0a0;
        --card-bg: #1a1008;
        --paper: #f6ecd9;
        --paper-2: #eaddc0;
        --ink: #2a1c10;
        --heart: #8a3a2a;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        min-height: 100%;
    }

    body {
        font-family: 'Cormorant Garamond', serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 16px;
        background: linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 100%);
        position: relative;
        overflow-x: hidden;
    }

    .blob {
        position: fixed;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.35;
        pointer-events: none;
        z-index: 0;
    }

    .blob.b1 {
        width: 340px;
        height: 340px;
        top: -90px;
        left: -90px;
        background: var(--accent);
    }

    .blob.b2 {
        width: 380px;
        height: 380px;
        bottom: -120px;
        right: -100px;
        background: var(--btn-bg);
        opacity: 0.2;
    }

    .stage {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        width: 100%;
    }

    .stage-title {
        text-align: center;
        color: var(--btn-bg);
        letter-spacing: 0.28em;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
        opacity: 0.85;
    }

    .frame {
        width: 460px;
        max-width: 92vw;
        background: linear-gradient(160deg, var(--btn-bg), var(--accent) 140%);
        border-radius: 26px;
        padding: 14px;
        box-shadow: 0 34px 70px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.12);
        position: relative;
    }

    .frame::before {
        content: '';
        position: absolute;
        top: -10px;
        left: 26px;
        width: 20px;
        height: 20px;
        background: var(--btn-bg);
        border-radius: 50%;
        box-shadow: 0 0 0 4px var(--accent);
    }

    .card {
        background: var(--paper);
        border-radius: 18px;
        padding: 18px 18px 22px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        grid-template-rows: 150px 122px 150px;
        gap: 10px;
    }

    .tile {
        border-radius: 8px;
        overflow: hidden;
        background: var(--paper-2);
        position: relative;
    }

    .tile svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .tile.walk {
        grid-column: 1;
        grid-row: 1;
    }

    .tile.name {
        grid-column: 2;
        grid-row: 1;
        background: var(--paper);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: right;
    }

    .tile.beach {
        grid-column: 1;
        grid-row: 2;
    }

    .tile.coffee {
        grid-column: 2;
        grid-row: 2;
    }

    .tile.embrace {
        grid-column: 1;
        grid-row: 3;
    }

    .tile.cal {
        grid-column: 2;
        grid-row: 3;
        background: var(--paper);
        padding: 10px 9px 6px;
    }

    .names {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        color: var(--bg-1);
        line-height: 0.95;
    }

    .names .first {
        font-size: 34px;
        display: block;
    }

    .names .second {
        font-size: 34px;
        display: block;
        color: var(--heart);
    }

    .cal-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 15px;
        color: var(--ink);
        margin: 0 0 6px;
        letter-spacing: 0.02em;
    }

    .cal-dow {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        font-size: 8.5px;
        letter-spacing: 0.03em;
        color: var(--accent);
        text-align: center;
        margin-bottom: 3px;
        font-weight: 600;
    }

    .cal-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: 16px;
        font-size: 10px;
        color: var(--ink);
        text-align: center;
    }

    .cal-days span {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cal-days span.muted {
        color: #b9ab92;
    }

    .cal-days span.marked {
        font-weight: 700;
        color: var(--ink);
    }

    .cal-days span.marked::after {
        content: '';
        position: absolute;
        width: 19px;
        height: 19px;
        background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%20viewBox%3D%270%200%2024%2024%27%3E%3Cpath%20d%3D%27M12%2021s-7.5-4.6-10.2-9.6C.3%208.4%201.7%205%205%204.2c2.1-.5%204%20.4%205%202.1%201-1.7%202.9-2.6%205-2.1%203.3.8%204.7%204.2%203.2%207.2C19.5%2016.4%2012%2021%2012%2021z%27%20fill%3D%27none%27%20stroke%3D%27%238a3a2a%27%20stroke-width%3D%271.6%27/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        pointer-events: none;
    }

    /* ---------- note / message section ---------- */
    .note {
        margin-top: 16px;
        padding: 16px 18px;
        background: var(--paper-2);
        border-radius: 12px;
        position: relative;
    }

    .note::before {
        content: '“';
        position: absolute;
        top: -6px;
        left: 12px;
        font-family: 'Dancing Script', cursive;
        font-size: 46px;
        color: var(--accent);
        opacity: 0.55;
    }

    .note p {
        margin: 0;
        padding-left: 14px;
        font-style: italic;
        font-size: 15px;
        line-height: 1.6;
        color: var(--ink);
    }

    .note .signed {
        display: block;
        margin-top: 8px;
        padding-left: 14px;
        font-family: 'Dancing Script', cursive;
        font-style: normal;
        font-size: 18px;
        color: var(--heart);
    }

    .caption {
        text-align: center;
        margin-top: 16px;
        font-size: 12.5px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #7a6a52;
    }

    .theme-badge {
        text-align: center;
        font-size: 11.5px;
        letter-spacing: 0.1em;
        color: var(--btn-bg);
        margin-top: 2px;
        text-transform: uppercase;
        opacity: 0.8;
    }

    @media (max-width: 480px) {
        .frame {
            width: 100%;
            padding: 10px;
        }

        .card {
            padding: 14px 14px 18px;
        }

        .grid {
            grid-template-rows: 124px 100px 124px;
            gap: 8px;
        }

        .names .first,
        .names .second {
            font-size: 27px;
        }

        .note p {
            font-size: 14px;
        }
    }
    </style>
</head>

<body>

    <div class="blob b1"></div>
    <div class="blob b2"></div>

    <div class="stage">
        <div class="stage-title">Warm Amber</div>

        <div class="frame">
            <div class="card">
                <div class="grid">

                    <div class="tile walk">
                        @if(request('photo1'))
                        <img src="{{ request('photo1') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <svg viewBox="0 0 200 170" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="skyWalk" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0" stop-color="#e9dcc2" />
                                    <stop offset="1" stop-color="#cbb692" />
                                </linearGradient>
                            </defs>
                            <rect width="200" height="170" fill="url(#skyWalk)" />
                            <rect x="0" y="90" width="200" height="80" fill="#a68f6a" opacity="0.5" />
                            <rect x="10" y="40" width="30" height="55" fill="#8a7454" opacity="0.6" />
                            <rect x="45" y="55" width="26" height="40" fill="#8a7454" opacity="0.5" />
                            <rect x="150" y="45" width="34" height="50" fill="#8a7454" opacity="0.55" />
                            <rect x="0" y="140" width="200" height="4" fill="#8a7454" opacity="0.4" />
                            <g transform="translate(95,95)">
                                <circle cx="0" cy="-18" r="7" fill="var(--card-bg)" />
                                <path d="M-8 -4 Q0 -12 8 -4 L10 30 L-10 30 Z" fill="var(--card-bg)" />
                                <circle cx="17" cy="-16" r="7" fill="var(--card-bg)" />
                                <path d="M9 -3 Q17 -10 25 -3 L27 32 L7 32 Z" fill="var(--card-bg)" />
                            </g>
                        </svg>
                        @endif
                    </div>

                    <div class="tile name">
                        <div class="names">
                            <span class="first">{{ request('name_first', 'Emma') }}</span>
                            <span class="second">{{ request('name_second', 'Lucas') }}</span>
                        </div>
                    </div>

                    <div class="tile beach">
                        @if(request('photo2'))
                        <img src="{{ request('photo2') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <svg viewBox="0 0 200 140" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="beachSky" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0" stop-color="#e9dcc2" />
                                    <stop offset="0.55" stop-color="#cbb692" />
                                    <stop offset="0.55" stop-color="#a68f6a" />
                                    <stop offset="1" stop-color="#8a7454" />
                                </linearGradient>
                            </defs>
                            <rect width="200" height="140" fill="url(#beachSky)" />
                            <g transform="translate(90,58)">
                                <circle cx="0" cy="-8" r="6" fill="var(--card-bg)" />
                                <path d="M-6 2 Q0 -6 8 0 L14 26 L-4 24 Z" fill="var(--card-bg)" />
                                <circle cx="18" cy="18" r="6" fill="var(--card-bg)" />
                                <path d="M8 24 Q18 10 26 18 L20 40 L4 36 Z" fill="var(--card-bg)" />
                            </g>
                        </svg>
                        @endif
                    </div>

                    <div class="tile coffee">
                        @if(request('photo3'))
                        <img src="{{ request('photo3') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <svg viewBox="0 0 200 140" xmlns="http://www.w3.org/2000/svg">
                            <rect width="200" height="140" fill="#eaddc0" />
                            <rect x="0" y="0" width="200" height="55" fill="#a68f6a" />
                            <ellipse cx="60" cy="95" rx="26" ry="22" fill="var(--card-bg)" />
                            <ellipse cx="60" cy="86" rx="20" ry="8" fill="#1a1008" />
                            <ellipse cx="140" cy="95" rx="26" ry="22" fill="var(--card-bg)" />
                            <ellipse cx="140" cy="86" rx="20" ry="8" fill="#1a1008" />
                            <rect x="35" y="60" width="130" height="14" rx="7" fill="#1a1008" opacity="0.55" />
                            <circle cx="150" cy="63" r="5" fill="var(--heart)" />
                        </svg>
                        @endif
                    </div>

                    <div class="tile embrace">
                        @if(request('photo4'))
                        <img src="{{ request('photo4') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <svg viewBox="0 0 200 170" xmlns="http://www.w3.org/2000/svg">
                            <rect width="200" height="170" fill="#a68f6a" />
                            <g transform="translate(100,90)">
                                <circle cx="-10" cy="-30" r="9" fill="var(--card-bg)" />
                                <path d="M-24 -12 Q-10 -22 4 -12 L8 40 L-28 40 Z" fill="var(--card-bg)" />
                                <circle cx="14" cy="-34" r="9" fill="var(--card-bg)" />
                                <path d="M0 -16 Q14 -26 28 -16 L32 44 L-4 44 Z" fill="var(--card-bg)" opacity="0.92" />
                            </g>
                        </svg>
                        @endif
                    </div>

                    @php
                        $calDays = [
                            'lead' => [31],
                            'days' => range(1, 30),
                            'trail' => [1, 2, 3, 4],
                        ];
                        $markedDay = (int) request('cal_day', 13);
                    @endphp
                    <div class="tile cal">
                        <p class="cal-title">{{ request('cal_month', 'September 2020') }}</p>
                        <div class="cal-dow">
                            <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
                        </div>
                        <div class="cal-days">
                            @foreach($calDays['lead'] as $d)<span class="muted">{{ $d }}</span>@endforeach
                            @foreach($calDays['days'] as $d)<span
                                @if($d === $markedDay) class="marked" @endif>{{ $d }}</span>@endforeach
                            @foreach($calDays['trail'] as $d)<span class="muted">{{ $d }}</span>@endforeach
                        </div>
                    </div>

                </div>

                <div class="note">
                    <p>{{ request('message', "Every road we've walked has led me back to you — again and again, gladly.") }}</p>
                    <span class="signed">{{ request('signed', '— always, E') }}</span>
                </div>

                <div class="caption">est. together</div>
                <div class="theme-badge">Wrapped warm &amp; amber</div>
            </div>
        </div>
    </div>

</body>

</html>