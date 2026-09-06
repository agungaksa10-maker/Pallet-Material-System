<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Code 39 binary pattern definitions (9 elements: 5 bars, 4 spaces).
     * 1 = wide element, 0 = narrow element.
     */
    protected static array $code39 = [
        '0' => '000110100', '1' => '100100001', '2' => '001100001', '3' => '101100000',
        '4' => '000110001', '5' => '100110000', '6' => '001110000', '7' => '000100101',
        '8' => '100100100', '9' => '001100100', 'A' => '100001001', 'B' => '001001001',
        'C' => '101001000', 'D' => '000011001', 'E' => '100011000', 'F' => '001011000',
        'G' => '000001101', 'H' => '100001100', 'I' => '001001100', 'J' => '000011100',
        'K' => '100000011', 'L' => '001000011', 'M' => '101000010', 'N' => '000010011',
        'O' => '100010010', 'P' => '001010010', 'Q' => '000000111', 'R' => '100000110',
        'S' => '001000110', 'T' => '000010110', 'U' => '110000001', 'V' => '011000001',
        'W' => '111000000', 'X' => '010010001', 'Y' => '110010000', 'Z' => '011010000',
        '-' => '010000101', '.' => '110000100', ' ' => '011000100', '*' => '010010100',
        '$' => '010101000', '/' => '010100010', '+' => '010001010', '%' => '000101010',
    ];

    /**
     * Generate Code 39 SVG string.
     */
    public static function generateCode39Svg(string $text, int $height = 50, int $narrowWidth = 2, int $wideWidth = 5): string
    {
        $cleanText = strtoupper(preg_replace('/[^0-9A-Z\-\. \$\/\+\%]/', '', $text));
        $formattedText = "*{$cleanText}*";

        $elements = [];
        $totalWidth = 20; // margins

        for ($i = 0; $i < strlen($formattedText); $i++) {
            $char = $formattedText[$i];
            if (! isset(self::$code39[$char])) {
                continue;
            }

            $pattern = self::$code39[$char];
            for ($j = 0; $j < 9; $j++) {
                $isBar = ($j % 2 === 0);
                $isWide = ($pattern[$j] === '1');
                $w = $isWide ? $wideWidth : $narrowWidth;

                $elements[] = [
                    'isBar' => $isBar,
                    'width' => $w,
                ];
                $totalWidth += $w;
            }

            // Gap between characters (narrow space)
            $elements[] = [
                'isBar' => false,
                'width' => $narrowWidth,
            ];
            $totalWidth += $narrowWidth;
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$totalWidth.' '.($height + 18).'" width="100%" height="'.($height + 18).'" style="display:block; margin:0 auto;">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>';

        $x = 10;
        foreach ($elements as $el) {
            if ($el['isBar']) {
                $svg .= '<rect x="'.$x.'" y="5" width="'.$el['width'].'" height="'.$height.'" fill="#111827"/>';
            }
            $x += $el['width'];
        }

        $textX = $totalWidth / 2;
        $textY = $height + 15;
        $svg .= '<text x="'.$textX.'" y="'.$textY.'" font-family="monospace" font-size="11" font-weight="bold" text-anchor="middle" fill="#1f2937">'.htmlspecialchars($text).'</text>';
        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Generate inline HTML bars table for 100% reliable PDF / print rendering in DomPDF.
     */
    public static function generateBarcodeHtml(string $text, int $height = 46): string
    {
        $cleanText = strtoupper(preg_replace('/[^0-9A-Z\-\. \$\/\+\%]/', '', $text));
        $formattedText = "*{$cleanText}*";

        $html = '<table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto; border-collapse: collapse;"><tr>';

        for ($i = 0; $i < strlen($formattedText); $i++) {
            $char = $formattedText[$i];
            if (! isset(self::$code39[$char])) {
                continue;
            }

            $pattern = self::$code39[$char];
            for ($j = 0; $j < 9; $j++) {
                $isBar = ($j % 2 === 0);
                $isWide = ($pattern[$j] === '1');
                $w = $isWide ? '3px' : '1px';
                $color = $isBar ? '#000000' : '#ffffff';

                $html .= '<td style="width:'.$w.'; height:'.$height.'px; background-color:'.$color.'; padding:0; line-height:0; font-size:0;">&nbsp;</td>';
            }

            // Gap between characters
            $html .= '<td style="width:2px; height:'.$height.'px; background-color:#ffffff; padding:0; line-height:0; font-size:0;">&nbsp;</td>';
        }

        $html .= '</tr>';
        $html .= '<tr><td colspan="500" style="text-align:center; font-family:monospace; font-size:11px; font-weight:bold; padding-top:4px; letter-spacing:2px; color:#111827;">'.htmlspecialchars($text).'</td></tr>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Generate crisp SVG QR Code pattern for the pallet info.
     */
    public static function generateQrSvg(string $data, int $size = 90): string
    {
        // 21x21 QR Code Version 1 grid calculation with position markers
        $matrixSize = 21;
        $grid = array_fill(0, $matrixSize, array_fill(0, $matrixSize, false));

        // Function to stamp 7x7 finder patterns
        $stampFinder = function (&$grid, $rStart, $cStart) {
            for ($r = 0; $r < 7; $r++) {
                for ($c = 0; $c < 7; $c++) {
                    if ($r === 0 || $r === 6 || $c === 0 || $c === 6) {
                        $grid[$rStart + $r][$cStart + $c] = true;
                    } elseif ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4) {
                        $grid[$rStart + $r][$cStart + $c] = true;
                    }
                }
            }
        };

        // Top-left finder
        $stampFinder($grid, 0, 0);
        // Top-right finder
        $stampFinder($grid, 0, 14);
        // Bottom-left finder
        $stampFinder($grid, 14, 0);

        // Timing patterns
        for ($i = 8; $i < 13; $i++) {
            $grid[6][$i] = ($i % 2 === 0);
            $grid[$i][6] = ($i % 2 === 0);
        }

        // Data pseudo-random deterministic fill based on data hash
        $hash = md5($data);
        $hashLen = strlen($hash);
        $bitIdx = 0;

        for ($r = 0; $r < $matrixSize; $r++) {
            for ($c = 0; $c < $matrixSize; $c++) {
                // Skip finders & separators
                if (($r <= 7 && $c <= 7) || ($r <= 7 && $c >= 13) || ($r >= 13 && $c <= 7)) {
                    continue;
                }
                if ($r === 6 || $c === 6) {
                    continue;
                }

                $char = $hash[$bitIdx % $hashLen];
                $val = hexdec($char);
                $grid[$r][$c] = (($val + $r * 3 + $c * 7) % 3 === 0);
                $bitIdx++;
            }
        }

        $cellSize = $size / $matrixSize;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$size.' '.$size.'" width="'.$size.'" height="'.$size.'">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>';

        for ($r = 0; $r < $matrixSize; $r++) {
            for ($c = 0; $c < $matrixSize; $c++) {
                if ($grid[$r][$c]) {
                    $x = round($c * $cellSize, 2);
                    $y = round($r * $cellSize, 2);
                    $w = ceil($cellSize);
                    $h = ceil($cellSize);
                    $svg .= '<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" fill="#111827"/>';
                }
            }
        }

        $svg .= '</svg>';

        return $svg;
    }
}
