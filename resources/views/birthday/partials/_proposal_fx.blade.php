{{--
    Proposal · the particle layer.

    One canvas, one rAF loop, shared by all four designs. Before this existed
    every design carried its own confetti — four copies of the same maths, each
    drifting a little from the others.

    It is a *flavour* API rather than a particle API: a design asks for the
    thing it means ("hearts", "gold dust") and the engine owns how that looks,
    so two designs asking for confetti get the same confetti.

        pfx.burst('confetti', { x, y, count, colors, power })
        pfx.rain('hearts',    { count, colors, duration })
        pfx.meteors({ count })

    `x` / `y` default to the middle of the screen, colours to the page's own
    --fx-colors list, so a design that has set its theme colours can ask for a
    burst with no arguments at all.

    The loop only runs while there are particles on screen, and stops itself
    when the tab is hidden — a celebration nobody is looking at is just a
    battery drain. Under prefers-reduced-motion nothing is drawn at all: the
    page still moves through its states, it just does not throw anything.
--}}
<style>
    #pfxCanvas {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 60;
    }
</style>
<canvas id="pfxCanvas" aria-hidden="true"></canvas>
<script>
    window.pfx = (function () {
        var canvas = document.getElementById('pfxCanvas');
        var ctx = canvas.getContext('2d');
        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var parts = [];
        var running = false;
        var last = 0;

        function size() {
            var dpr = Math.min(window.devicePixelRatio || 1, 2);
            canvas.width = Math.floor(innerWidth * dpr);
            canvas.height = Math.floor(innerHeight * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }
        size();
        addEventListener('resize', size);

        /** The design's own palette, read off the page so a burst needs no arguments. */
        function themeColors() {
            var raw = getComputedStyle(document.documentElement).getPropertyValue('--fx-colors');
            var list = raw.split(',').map(function (c) { return c.trim(); }).filter(Boolean);
            return list.length ? list : ['#f5c86b', '#e3798f', '#ffffff', '#8fd4c2'];
        }

        function pick(list) { return list[(Math.random() * list.length) | 0]; }

        function tick(now) {
            var dt = Math.min((now - last) / 16.67, 2.4);
            last = now;
            ctx.clearRect(0, 0, innerWidth, innerHeight);

            for (var i = parts.length - 1; i >= 0; i--) {
                var p = parts[i];
                p.life -= dt;
                if (p.life <= 0) { parts.splice(i, 1); continue; }

                p.vy += p.g * dt;
                p.vx *= p.drag;
                p.vy *= p.drag;
                p.x += p.vx * dt;
                p.y += p.vy * dt;
                p.rot += p.spin * dt;

                var alpha = p.fade ? Math.max(0, Math.min(1, p.life / p.fadeOver)) : 1;
                ctx.globalAlpha = alpha;
                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate(p.rot);
                draw[p.kind](ctx, p);
                ctx.restore();
            }
            ctx.globalAlpha = 1;

            if (parts.length && !document.hidden) {
                requestAnimationFrame(tick);
            } else {
                running = false;
                ctx.clearRect(0, 0, innerWidth, innerHeight);
                parts.length = 0;
            }
        }

        function start() {
            if (running || reduced) return;
            running = true;
            last = performance.now();
            requestAnimationFrame(tick);
        }

        /* ── how each flavour is drawn ─────────────────────────── */
        var draw = {
            confetti: function (c, p) {
                c.fillStyle = p.color;
                // a rectangle scaled on one axis reads as a tumbling ribbon
                c.fillRect(-p.r, -p.r * 0.42, p.r * 2, p.r * 0.84 * Math.abs(Math.cos(p.rot * 1.6)) + 1);
            },
            heart: function (c, p) {
                c.fillStyle = p.color;
                var s = p.r / 12;
                c.scale(s, s);
                c.beginPath();
                c.moveTo(0, 4);
                c.bezierCurveTo(-11, -5, -7, -14, 0, -8);
                c.bezierCurveTo(7, -14, 11, -5, 0, 4);
                c.fill();
            },
            petal: function (c, p) {
                c.fillStyle = p.color;
                c.beginPath();
                c.ellipse(0, 0, p.r, p.r * 0.52, 0, 0, Math.PI * 2);
                c.fill();
            },
            dust: function (c, p) {
                c.fillStyle = p.color;
                c.beginPath();
                c.arc(0, 0, p.r * 0.45, 0, Math.PI * 2);
                c.fill();
            },
            star: function (c, p) {
                c.fillStyle = p.color;
                c.beginPath();
                for (var i = 0; i < 4; i++) {
                    var a = (Math.PI / 2) * i;
                    c.lineTo(Math.cos(a) * p.r, Math.sin(a) * p.r);
                    c.lineTo(Math.cos(a + Math.PI / 4) * p.r * 0.32,
                             Math.sin(a + Math.PI / 4) * p.r * 0.32);
                }
                c.fill();
            },
            meteor: function (c, p) {
                var g = c.createLinearGradient(0, 0, -p.r * 7, p.r * 7);
                g.addColorStop(0, p.color);
                g.addColorStop(1, 'rgba(255,255,255,0)');
                c.strokeStyle = g;
                c.lineWidth = p.r * 0.5;
                c.lineCap = 'round';
                c.beginPath();
                c.moveTo(0, 0);
                c.lineTo(-p.r * 7, p.r * 7);
                c.stroke();
            },
        };

        /* ── the flavours themselves ───────────────────────────── */
        var flavour = {
            confetti: { kind: 'confetti', r: [4, 9], g: 0.16, drag: 0.992, life: [90, 150], spin: 0.3 },
            hearts:   { kind: 'heart',    r: [9, 18], g: 0.07, drag: 0.987, life: [90, 160], spin: 0.08 },
            petals:   { kind: 'petal',    r: [6, 13], g: 0.05, drag: 0.985, life: [110, 190], spin: 0.12 },
            dust:     { kind: 'dust',     r: [2, 7],  g: 0.12, drag: 0.978, life: [50, 110], spin: 0.02 },
            stars:    { kind: 'star',     r: [4, 10], g: 0.02, drag: 0.99,  life: [80, 150], spin: 0.06 },
        };

        function between(range) { return range[0] + Math.random() * (range[1] - range[0]); }

        function spawn(f, x, y, vx, vy, colors) {
            parts.push({
                kind: f.kind, x: x, y: y, vx: vx, vy: vy,
                r: between(f.r), g: f.g, drag: f.drag,
                life: between(f.life), fade: true, fadeOver: 45,
                rot: Math.random() * Math.PI * 2,
                spin: (Math.random() - 0.5) * f.spin * 2,
                color: pick(colors),
            });
        }

        return {
            /** A cone of particles thrown out of one point. */
            burst: function (name, o) {
                if (reduced) return;
                o = o || {};
                var f = flavour[name] || flavour.confetti;
                var x = o.x == null ? innerWidth / 2 : o.x;
                var y = o.y == null ? innerHeight * 0.45 : o.y;
                var n = o.count || 60;
                var power = o.power || 9;
                var colors = o.colors || themeColors();
                for (var i = 0; i < n; i++) {
                    var a = -Math.PI / 2 + (Math.random() - 0.5) * (o.spread || Math.PI * 1.35);
                    var s = power * (0.35 + Math.random() * 0.9);
                    spawn(f, x, y, Math.cos(a) * s, Math.sin(a) * s, colors);
                }
                start();
            },

            /** Particles falling in from above the fold, over a window of time. */
            rain: function (name, o) {
                if (reduced) return;
                o = o || {};
                var f = flavour[name] || flavour.petals;
                var n = o.count || 40;
                var colors = o.colors || themeColors();
                var over = o.duration || 900;
                for (var i = 0; i < n; i++) {
                    (function (d) {
                        setTimeout(function () {
                            spawn(f, Math.random() * innerWidth, -30,
                                (Math.random() - 0.5) * 1.6, 1.2 + Math.random() * 2.2, colors);
                            start();
                        }, d);
                    })((i / n) * over);
                }
            },

            /** Streaks across the top third — the night-sky celebration. */
            meteors: function (o) {
                if (reduced) return;
                o = o || {};
                var colors = o.colors || themeColors();
                var n = o.count || 7;
                for (var i = 0; i < n; i++) {
                    (function (d) {
                        setTimeout(function () {
                            parts.push({
                                kind: 'meteor',
                                x: Math.random() * innerWidth * 0.7 + innerWidth * 0.3,
                                y: -20 + Math.random() * innerHeight * 0.25,
                                vx: -7 - Math.random() * 4, vy: 7 + Math.random() * 4,
                                r: 2 + Math.random() * 2.4, g: 0, drag: 1,
                                life: 70, fade: true, fadeOver: 60,
                                rot: 0, spin: 0, color: pick(colors),
                            });
                            start();
                        }, d);
                    })(i * 180 + Math.random() * 120);
                }
            },

            /** Put the layer back to nothing — the looping demo starting over. */
            clear: function () {
                parts.length = 0;
                ctx.clearRect(0, 0, innerWidth, innerHeight);
            },
        };
    })();
</script>
