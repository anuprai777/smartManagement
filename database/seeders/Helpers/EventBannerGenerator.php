<?php

namespace Database\Seeders\Helpers;

use Illuminate\Support\Facades\Storage;

class EventBannerGenerator
{
    /**
     * Color themes for different event categories.
     */
    protected static array $themes = [
        'tech'       => [[41, 53, 147], [100, 43, 155], '#293593', '#642B9B'],
        'creative'   => [[219, 39, 119], [147, 51, 234], '#DB2777', '#9333EA'],
        'business'   => [[15, 116, 189], [0, 150, 136], '#0F74BD', '#009688'],
        'health'     => [[16, 137, 62], [34, 150, 100], '#10893E', '#229664'],
        'music'      => [[220, 38, 38], [180, 30, 100], '#DC2626', '#B41E64'],
        'education'  => [[30, 64, 175], [79, 70, 229], '#1E40AF', '#4F46E5'],
        'social'     => [[234, 88, 12], [220, 38, 100], '#EA580C', '#DC2664'],
        'nature'     => [[22, 163, 74], [5, 150, 105], '#16A34A', '#059669'],
        'design'     => [[147, 51, 234], [79, 70, 229], '#9333EA', '#4F46E5'],
        'general'    => [[79, 70, 229], [147, 51, 234], '#4F46E5', '#9333EA'],
    ];

    /**
     * Generate a banner image for an event and store it.
     *
     * @param string $title       Event title to overlay on the banner
     * @param string $category    Theme category key
     * @param int    $width       Image width
     * @param int    $height      Image height
     * @return string|null        The stored path, or null on failure
     */
    public static function generate(string $title, string $category = 'general', int $width = 800, int $height = 400): ?string
    {
        if (! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $theme = self::$themes[$category] ?? self::$themes['general'];

        // Create image
        $image = imagecreatetruecolor($width, $height);
        if (! $image) {
            return null;
        }

        // Antialias
        imageantialias($image, true);

        // ── Draw gradient background ──
        self::drawGradient($image, $width, $height, $theme[0], $theme[1]);

        // ── Draw decorative elements ──
        self::drawCircles($image, $width, $height, $theme);
        self::drawGrid($image, $width, $height, $theme);

        // ── Draw title text ──
        self::drawTitle($image, $width, $height, $title);

        // ── Save image ──
        $filename = 'event-banners/' . md5($title . microtime()) . '.webp';
        $path = Storage::disk('public')->path($filename);

        // Ensure directory exists
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($image, $path, 85);
        imagedestroy($image);

        return $filename;
    }

    /**
     * Draw a vertical gradient from top to bottom.
     */
    protected static function drawGradient($image, int $w, int $h, array $color1, array $color2): void
    {
        for ($y = 0; $y < $h; $y++) {
            $r = (int) ($color1[0] + ($color2[0] - $color1[0]) * ($y / $h));
            $g = (int) ($color1[1] + ($color2[1] - $color1[1]) * ($y / $h));
            $b = (int) ($color1[2] + ($color2[2] - $color1[2]) * ($y / $h));
            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $w, $y, $color);
        }
    }

    /**
     * Draw decorative semi-transparent circles.
     */
    protected static function drawCircles($image, int $w, int $h, array $theme): void
    {
        $lightR = min(255, $theme[0][0] + 60);
        $lightG = min(255, $theme[0][1] + 60);
        $lightB = min(255, $theme[0][2] + 60);

        $positions = [
            [$w * 0.85, $h * 0.15, $w * 0.3],
            [$w * 0.1,  $h * 0.8,  $w * 0.25],
            [$w * 0.5,  $h * 0.5,  $w * 0.4],
            [$w * -0.1, $h * -0.1, $w * 0.2],
            [$w * 0.7,  $h * 0.7,  $w * 0.15],
        ];

        foreach ($positions as [$cx, $cy, $r]) {
            $color = imagecolorallocatealpha($image, $lightR, $lightG, $lightB, 60);
            imagefilledellipse($image, (int) $cx, (int) $cy, (int) $r, (int) $r, $color);
        }
    }

    /**
     * Draw a subtle grid pattern.
     */
    protected static function drawGrid($image, int $w, int $h, array $theme): void
    {
        $lineColor = imagecolorallocatealpha($image, 255, 255, 255, 85);

        // Vertical lines
        for ($x = 0; $x < $w; $x += 60) {
            imageline($image, $x, 0, $x, $h, $lineColor);
        }

        // Horizontal lines
        for ($y = 0; $y < $h; $y += 60) {
            imageline($image, 0, $y, $w, $y, $lineColor);
        }
    }

    /**
     * Draw the event title centered on the banner.
     */
    protected static function drawTitle($image, int $w, int $h, string $title): void
    {
        $white = imagecolorallocate($image, 255, 255, 255);
        $shadow = imagecolorallocatealpha($image, 0, 0, 0, 50);

        // Wrap title text
        $words = explode(' ', $title);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine ? $currentLine . ' ' . $word : $word;
            // Approximate: 10px per character at font size 28
            if (strlen($testLine) * 10 > $w * 0.85 && $currentLine) {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }
        if ($currentLine) {
            $lines[] = $currentLine;
        }

        $lineCount = count($lines);
        $fontSize = $lineCount > 2 ? 22 : ($lineCount > 1 ? 26 : 30);
        $startY = ($h / 2) - (($lineCount - 1) * ($fontSize + 10) / 2);

        // Try to use a bundled font, fall back to built-in
        $fontFile = null;
        $possibleFonts = [
            '/usr/share/fonts/TTF/trebuc.ttf',
            '/usr/share/fonts/TTF/Trebucbd.ttf',
            '/usr/share/fonts/noto-cjk/NotoSansCJK-Bold.ttc',
            '/usr/share/fonts/TTF/FiraCodeNerdFontPropo-Regular.ttf',
            '/usr/share/fonts/TTF/RobotoMono-Regular.ttf',
            storage_path('fonts/Inter-Bold.ttf'),
        ];

        foreach ($possibleFonts as $f) {
            if (file_exists($f)) {
                $fontFile = $f;
                break;
            }
        }

        foreach ($lines as $i => $line) {
            $y = (int) ($startY + $i * ($fontSize + 10));

            if ($fontFile) {
                $bbox = imagettfbbox($fontSize, 0, $fontFile, $line);
                $textW = $bbox[2] - $bbox[0];
                $x = (int) (($w - $textW) / 2);

                // Shadow
                imagettftext($image, $fontSize, 0, $x + 2, $y + 2, $shadow, $fontFile, $line);
                // Main text
                imagettftext($image, $fontSize, 0, $x, $y, $white, $fontFile, $line);
            } else {
                // Fallback: built-in font
                $charW = imagefontwidth(5);
                $charH = imagefontheight(5);
                $textW = strlen($line) * $charW;
                $x = (int) (($w - $textW) / 2);
                imagestring($image, 5, $x + 2, $y + 2 - $charH, $line, $shadow);
                imagestring($image, 5, $x, $y - $charH, $line, $white);
            }
        }
    }

    /**
     * Pick a theme category based on event title keywords.
     */
    public static function detectCategory(string $title): string
    {
        $title = strtolower($title);

        $map = [
            'tech|technology|tech|software|code|programming|developer|devops|cloud|cyber|security|data|blockchain|ai|machine learning|artificial intelligence|iot|sprint|hackathon|open source|startup' => 'tech',
            'design|ux|ui|creative|art|photography|exhibition|drawing|illustration|creative' => 'design',
            'market|business|entrepreneur|startup|e-commerce|ecommerce|leadership|management|pitch|finance|strategy' => 'business',
            'health|wellness|fitness|medical|yoga|meditation|nutrition' => 'health',
            'music|concert|band|orchestra|dj|production|audio|sound' => 'music',
            'workshop|seminar|bootcamp|masterclass|course|class|training|education|learn|lecture|summit' => 'education',
            'community|meetup|networking|social|charity|volunteer|fundraiser|festival|celebration' => 'social',
            'nature|outdoor|environment|eco|garden|climate|sustainable|green' => 'nature',
            'creative|art|design|craft|maker|diy' => 'creative',
        ];

        foreach ($map as $pattern => $category) {
            if (preg_match("/$pattern/i", $title)) {
                return $category;
            }
        }

        return 'general';
    }
}
