<?php

declare(strict_types=1);

namespace App\Support;

use Endroid\QrCode\Bacon\MatrixFactory;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

/**
 * Renders a QR code as a themed, self-contained SVG.
 *
 * endroid/qr-code does the encoding; the drawing is ours, because the four
 * QR themes differ in module shape, colour and framing — things its own
 * writers don't expose. Its PNG writer also needs the GD extension, which
 * this environment doesn't have, so SVG is the format either way. The SVG
 * carries no external references, so the dashboard can inline it as a data
 * URI and the browser can rasterise it to PNG on a canvas for downloads.
 *
 * Scannability is kept by the three things scanners actually need: the full
 * 4-module quiet zone, high error correction, and dark-on-light contrast in
 * every theme. Only the cosmetics change between themes.
 */
final class QrRenderer
{
    /** Quiet zone the QR spec asks for around the symbol, in modules. */
    private const QUIET_ZONE = 4;

    /** Each of the three finder patterns ("eyes") is 7x7 modules. */
    private const FINDER_SIZE = 7;

    /** @param array<string, mixed> $theme */
    public static function dataUri(string $data, array $theme, int $size = 360): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode(self::svg($data, $theme, $size));
    }

    /**
     * @param  array<string, mixed>  $theme  one entry of BirthdayCardController::QR_THEMES
     * @param  int  $size  width of the finished SVG in px (height follows the label)
     */
    public static function svg(string $data, array $theme, int $size = 360): string
    {
        $blocks = self::blocks($data);
        $count = count($blocks);

        // Lay the symbol out on a whole-pixel module grid so the modules stay
        // crisp; whatever rounding leaves over becomes extra quiet zone.
        $padding = (int) round($size * 0.09);
        $label = trim((string) ($theme['label'] ?? ''));
        $labelHeight = $label === '' ? 0 : (int) round($size * 0.13);

        $available = $size - 2 * $padding;
        $module = (int) floor($available / ($count + 2 * self::QUIET_ZONE));
        $module = max(1, $module);
        $symbol = $module * $count;
        $quiet = $module * self::QUIET_ZONE;

        $plateSize = $symbol + 2 * $quiet;
        $plateX = (int) round(($size - $plateSize) / 2);
        $plateY = $padding;
        $originX = $plateX + $quiet;
        $originY = $plateY + $quiet;

        $height = $plateY + $plateSize + $padding + $labelHeight;

        $bg = (string) ($theme['bg'] ?? '#ffffff');
        $plate = (string) ($theme['plate'] ?? '#ffffff');
        $moduleColor = (string) ($theme['module'] ?? '#000000');
        $moduleColorAlt = $theme['module_alt'] ?? null;
        $eyeFrame = (string) ($theme['eye_frame'] ?? $moduleColor);
        $eyeBall = (string) ($theme['eye_ball'] ?? $moduleColor);
        $shape = (string) ($theme['shape'] ?? 'square');
        $radius = (int) round($size * (float) ($theme['radius'] ?? 0.06));
        $plateRadius = (int) round($module * 2);

        $gradientId = 'qrg'.substr(md5($data.serialize($theme)), 0, 8);
        $fill = $moduleColorAlt ? "url(#{$gradientId})" : $moduleColor;

        $out = [];
        $out[] = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" role="img" aria-label="QR code">',
            $size,
            $height,
            $size,
            $height
        );

        if ($moduleColorAlt) {
            $out[] = sprintf(
                '<defs><linearGradient id="%s" x1="0" y1="0" x2="1" y2="1">'
                .'<stop offset="0" stop-color="%s"/><stop offset="1" stop-color="%s"/>'
                .'</linearGradient></defs>',
                $gradientId,
                self::attr($moduleColor),
                self::attr((string) $moduleColorAlt)
            );
        }

        $out[] = sprintf(
            '<rect x="0" y="0" width="%d" height="%d" rx="%d" fill="%s"/>',
            $size,
            $height,
            $radius,
            self::attr($bg)
        );

        // The plate keeps the quiet zone light even when the card itself is dark.
        $out[] = sprintf(
            '<rect x="%d" y="%d" width="%d" height="%d" rx="%d" fill="%s"/>',
            $plateX,
            $plateY,
            $plateSize,
            $plateSize,
            $plateRadius,
            self::attr($plate)
        );

        $frame = (string) ($theme['frame'] ?? 'none');
        if ($frame !== 'none') {
            $frameColor = self::attr((string) ($theme['frame_color'] ?? $moduleColor));
            $stroke = number_format(max(1.0, $size / 180), 2, '.', '');
            $dashes = $frame === 'dashed' ? sprintf(' stroke-dasharray="%d %d"', $module * 2, $module) : '';

            // 'double' draws a second, thinner line just inside the first —
            // a card border rather than a plain box.
            $insets = $frame === 'double'
                ? [(int) round($size * 0.028), (int) round($size * 0.055)]
                : [(int) round($size * 0.035)];

            foreach ($insets as $i => $inset) {
                $out[] = sprintf(
                    '<rect x="%d" y="%d" width="%d" height="%d" rx="%d" fill="none" stroke="%s" stroke-width="%s"%s/>',
                    $inset,
                    $inset,
                    $size - 2 * $inset,
                    $height - 2 * $inset,
                    max(0, $radius - $inset),
                    $frameColor,
                    $i === 0 ? $stroke : number_format(max(0.6, $size / 400), 2, '.', ''),
                    $dashes
                );
            }
        }

        // Data modules. The finder patterns are skipped here and drawn below,
        // so a theme can shape them differently without breaking the pattern
        // scanners look for.
        $out[] = self::modules($blocks, $count, $originX, $originY, $module, $shape, $fill);

        foreach (self::finderOrigins($count) as [$row, $col]) {
            $out[] = self::eye(
                $originX + $col * $module,
                $originY + $row * $module,
                $module,
                $shape,
                $eyeFrame,
                $eyeBall
            );
        }

        // Decorative corner motifs — the girl themes' own flourish. They are
        // drawn in the card's outer margin only, clear of the plate and its
        // quiet zone, so nothing a scanner reads is touched.
        $motif = (string) ($theme['motif'] ?? 'none');
        if ($motif !== 'none') {
            $out[] = self::motifs(
                $motif,
                $size,
                $height,
                $plateX,
                $plateY,
                $plateSize,
                self::attr((string) ($theme['motif_color'] ?? ($theme['frame_color'] ?? $moduleColor)))
            );
        }

        if ($label !== '') {
            $out[] = sprintf(
                '<text x="%d" y="%d" text-anchor="middle" font-family="Georgia, \'Times New Roman\', serif" '
                .'font-size="%d" letter-spacing="%s" fill="%s">%s</text>',
                (int) round($size / 2),
                $height - (int) round($labelHeight * 0.32),
                (int) round($labelHeight * 0.44),
                number_format($labelHeight * 0.05, 2, '.', ''),
                self::attr((string) ($theme['label_color'] ?? $moduleColor)),
                htmlspecialchars($label, ENT_QUOTES | ENT_XML1, 'UTF-8')
            );
        }

        $out[] = '</svg>';

        return implode('', $out);
    }

    /**
     * The encoded symbol as a square array of 0/1 rows.
     *
     * @return array<int, array<int, int>>
     */
    private static function blocks(string $data): array
    {
        $matrix = (new MatrixFactory())->create(new QrCode(
            data: $data,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
        ));

        $count = $matrix->getBlockCount();
        $blocks = [];
        for ($row = 0; $row < $count; $row++) {
            for ($col = 0; $col < $count; $col++) {
                $blocks[$row][$col] = $matrix->getBlockValue($row, $col);
            }
        }

        return $blocks;
    }

    /** Top-left corner of each finder pattern, in module coordinates. */
    private static function finderOrigins(int $count): array
    {
        $far = $count - self::FINDER_SIZE;

        return [[0, 0], [0, $far], [$far, 0]];
    }

    private static function inFinder(int $row, int $col, int $count): bool
    {
        foreach (self::finderOrigins($count) as [$fRow, $fCol]) {
            if ($row >= $fRow && $row < $fRow + self::FINDER_SIZE
                && $col >= $fCol && $col < $fCol + self::FINDER_SIZE) {
                return true;
            }
        }

        return false;
    }

    /** @param array<int, array<int, int>> $blocks */
    private static function modules(
        array $blocks,
        int $count,
        int $originX,
        int $originY,
        int $module,
        string $shape,
        string $fill
    ): string {
        // Square modules go out as one path — a few hundred <rect> elements
        // per QR adds up fast when the dashboard shows five of them at once.
        if ($shape === 'square') {
            $path = '';
            for ($row = 0; $row < $count; $row++) {
                for ($col = 0; $col < $count; $col++) {
                    if ($blocks[$row][$col] !== 1 || self::inFinder($row, $col, $count)) {
                        continue;
                    }
                    $path .= sprintf(
                        'M%d %dh%dv%dh-%dz',
                        $originX + $col * $module,
                        $originY + $row * $module,
                        $module,
                        $module,
                        $module
                    );
                }
            }

            return sprintf('<path fill="%s" d="%s"/>', self::attr($fill), $path);
        }

        $parts = ['<g fill="'.self::attr($fill).'">'];
        $gap = $shape === 'dot' ? 0 : max(0, (int) round($module * 0.08));
        $radius = $shape === 'dot' ? 0 : max(1, (int) round($module * 0.34));

        for ($row = 0; $row < $count; $row++) {
            for ($col = 0; $col < $count; $col++) {
                if ($blocks[$row][$col] !== 1 || self::inFinder($row, $col, $count)) {
                    continue;
                }
                $x = $originX + $col * $module;
                $y = $originY + $row * $module;

                $parts[] = $shape === 'dot'
                    ? sprintf(
                        '<circle cx="%s" cy="%s" r="%s"/>',
                        self::num($x + $module / 2),
                        self::num($y + $module / 2),
                        self::num($module * 0.46)
                    )
                    : sprintf(
                        '<rect x="%d" y="%d" width="%d" height="%d" rx="%d"/>',
                        $x + $gap,
                        $y + $gap,
                        $module - 2 * $gap,
                        $module - 2 * $gap,
                        $radius
                    );
            }
        }
        $parts[] = '</g>';

        return implode('', $parts);
    }

    /**
     * One finder pattern: a 7x7 ring one module thick with a 3x3 centre.
     * Drawn as two shapes rather than 33 modules so it can be rounded off
     * without eating into the ring's own quiet gap.
     */
    private static function eye(
        int $x,
        int $y,
        int $module,
        string $shape,
        string $frameColor,
        string $ballColor
    ): string {
        $outer = $module * self::FINDER_SIZE;

        // The eyes are only softened, never turned into circles: a scanner
        // locates the symbol by these three patterns, so every module of the
        // ring has to stay filled at its own centre. A corner radius below
        // one module keeps that true while still reading as "rounded".
        $round = $shape === 'square' ? 0 : (int) round($module * 0.6);
        $ballRound = $shape === 'square' ? 0 : (int) round($module * 0.5);

        return sprintf(
            '<rect x="%s" y="%s" width="%s" height="%s" rx="%d" fill="none" stroke="%s" stroke-width="%d"/>'
            .'<rect x="%d" y="%d" width="%d" height="%d" rx="%d" fill="%s"/>',
            self::num($x + $module / 2),
            self::num($y + $module / 2),
            self::num($outer - $module),
            self::num($outer - $module),
            $round,
            self::attr($frameColor),
            $module,
            $x + 2 * $module,
            $y + 2 * $module,
            3 * $module,
            3 * $module,
            $ballRound,
            self::attr($ballColor)
        );
    }

    /**
     * The corner flourishes. Each motif is drawn four times, once per corner of
     * the card, in the band between the card's edge and the plate — the widest
     * gap available, and never inside the symbol.
     */
    private static function motifs(
        string $motif,
        int $size,
        int $height,
        int $plateX,
        int $plateY,
        int $plateSize,
        string $color
    ): string {
        $unit = max(6, (int) round($size * 0.045));

        // Halfway between the card corner and the plate corner.
        $inset = max(4, (int) round(min($plateX, $plateY) / 2));
        $corners = [
            [$inset, $inset, 0],
            [$size - $inset, $inset, 90],
            [$size - $inset, $plateY + $plateSize + $inset, 180],
            [$inset, $plateY + $plateSize + $inset, 270],
        ];

        $shape = match ($motif) {
            // A small heart, drawn from its own centre.
            'heart' => sprintf(
                '<path d="M0 %1$s C %2$s %3$s, %4$s %5$s, 0 %6$s C %7$s %5$s, %8$s %3$s, 0 %1$s Z"/>',
                self::num(-$unit * 0.18),
                self::num($unit * 0.55),
                self::num(-$unit * 0.85),
                self::num($unit * 0.95),
                self::num($unit * 0.28),
                self::num($unit * 0.72),
                self::num(-$unit * 0.95),
                self::num(-$unit * 0.55)
            ),
            // Four petals around a centre.
            'petal' => sprintf(
                '<g>%s</g>',
                implode('', array_map(
                    fn ($angle) => sprintf(
                        '<ellipse cx="0" cy="%s" rx="%s" ry="%s" transform="rotate(%d)"/>',
                        self::num(-$unit * 0.42),
                        self::num($unit * 0.24),
                        self::num($unit * 0.44),
                        $angle
                    ),
                    [0, 90, 180, 270]
                ))
            ),
            // A four-pointed sparkle.
            'sparkle' => sprintf(
                '<path d="M0 %1$s Q %2$s %2$s, %3$s 0 Q %2$s %4$s, 0 %5$s Q %4$s %4$s, %6$s 0 Q %4$s %2$s, 0 %1$s Z"/>',
                self::num(-$unit * 0.72),
                self::num($unit * 0.14),
                self::num($unit * 0.72),
                self::num(-$unit * 0.14),
                self::num($unit * 0.72),
                self::num(-$unit * 0.72)
            ),
            default => '',
        };

        if ($shape === '') {
            return '';
        }

        $parts = [sprintf('<g fill="%s" opacity="0.9">', $color)];
        foreach ($corners as [$x, $y, $rotation]) {
            $parts[] = sprintf(
                '<g transform="translate(%d %d) rotate(%d)">%s</g>',
                $x,
                $y,
                $rotation,
                $shape
            );
        }
        $parts[] = '</g>';

        return implode('', $parts);
    }

    private static function num(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    private static function attr(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
