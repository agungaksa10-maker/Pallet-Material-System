<?php
require __DIR__ . '/vendor/autoload.php';

$overlayHtml = '
<div style="position: relative; width: 370pt; height: 40pt; margin: 0 auto;">
    <svg viewBox="0 0 620 66" width="370pt" height="40pt" style="position: absolute; top: 0; left: 0;">
        <polygon points="15,62 36,62 58,4 37,4" fill="#0047BA" />
        <polygon points="49,62 571,62 593,4 71,4" fill="#0047BA" />
        <polygon points="584,62 605,62 627,4 606,4" fill="#0047BA" />
    </svg>
    <div style="position: absolute; top: 3pt; left: 0; width: 370pt; text-align: center; color: #ffffff; font-size: 23pt; font-weight: 900; font-family: Arial, Helvetica, sans-serif; letter-spacing: 2pt;">
        I-COS
    </div>
</div>
';

$dompdf = new \Dompdf\Dompdf(['isHtml5ParserEnabled' => true]);
$dompdf->setPaper([0, 0, 430, 345], 'landscape');
$dompdf->loadHtml($overlayHtml);
$dompdf->render();
$pdfOutput = $dompdf->output(['compress' => 0]);
echo "Found I-COS in overlay PDF: " . (strpos($pdfOutput, 'I-COS') !== false ? 'YES' : 'NO') . "\n";
file_put_contents(__DIR__ . '/public/test_overlay.html', $overlayHtml);
file_put_contents(__DIR__ . '/public/test_overlay.pdf', $dompdf->output());
