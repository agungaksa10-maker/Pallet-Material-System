<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pallet Sticker Label</title>
    <style>
        @page {
            margin: 0;
            size: 430pt 345pt landscape;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 10pt;
            background-color: #ffffff;
            color: #000000;
        }
        .page-break {
            page-break-after: always;
        }
        .sticker-card {
            width: 100%;
            height: 325pt;
            border: 6pt solid #0047BA;
            border-radius: 18pt;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        .inner-content {
            padding: 12pt 16pt 0 16pt;
        }
        .andritz-logo-container {
            text-align: center;
            padding-bottom: 6pt;
        }
        .blue-divider {
            height: 3pt;
            background-color: #0047BA;
            width: 100%;
            margin: 0 auto;
        }
        .hero-row {
            width: 100%;
            margin: 8pt auto 6pt auto;
            border-collapse: collapse;
        }
        .hero-dash {
            width: 55pt;
            height: 5pt;
            background-color: #0047BA;
            display: inline-block;
            vertical-align: middle;
        }
        .hero-number {
            font-size: 34pt;
            font-weight: 900;
            color: #000000;
            letter-spacing: -0.5pt;
            line-height: 1;
            padding: 0 10pt;
            font-family: Arial, Helvetica, sans-serif;
        }
        .blue-banner {
            background-color: #0047BA;
            color: #ffffff;
            font-size: 26pt;
            font-weight: 900;
            letter-spacing: 2pt;
            text-align: center;
            padding: 4pt 0;
            margin: 4pt auto 0 auto;
            width: 95%;
            font-family: Arial, Helvetica, sans-serif;
        }
        .category-title {
            font-size: 19pt;
            font-weight: 900;
            color: #000000;
            text-align: center;
            margin-top: 5pt;
            margin-bottom: 8pt;
            letter-spacing: 0.5pt;
            font-family: Arial, Helvetica, sans-serif;
        }
        .warning-table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .warning-box {
            border: 2.5pt solid #0047BA;
            border-radius: 6pt;
            padding: 7pt 10pt;
            text-align: center;
            font-size: 14pt;
            font-weight: 900;
            color: #000000;
            font-family: Arial, Helvetica, sans-serif;
            background: #ffffff;
        }
        .footer-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #0047BA;
            color: #ffffff;
            padding: 6pt 14pt;
            height: 38pt;
            box-sizing: border-box;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .phone-text {
            font-size: 13pt;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.3pt;
            font-family: Arial, Helvetica, sans-serif;
            vertical-align: middle;
            white-space: nowrap;
        }
        .icon-box {
            display: inline-block;
            width: 24pt;
            height: 24pt;
            border: 1.5pt solid #ffffff;
            border-radius: 3pt;
            text-align: center;
            vertical-align: middle;
            margin-left: 4pt;
            padding: 1.5pt;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

@foreach($stickers as $index => $item)
    @php
        $catCode = $item['category_code'] ?? 'I-COS';
        $catTitle = ($item['category'] === 'Dressing') ? 'Dressing Material' : 'Consumable Material';
        $palletNum = $item['pallet_number'];
    @endphp

    <div class="sticker-card {{ !$loop->last ? 'page-break' : '' }}">
        
        <div class="inner-content">
            <!-- 1. OFFICIAL ANDRITZ LOGO HEADER -->
            <div class="andritz-logo-container">
                <svg viewBox="0 0 1065 210" width="270pt" height="42pt" xmlns="http://www.w3.org/2000/svg">
                    <g transform="translate(120, -290)" fill="#0047BA">
                        <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                        <path d="M 574.18111,293.58229 L 574.18111,352.9187 L 654.22461,352.9187 L 654.22461,495.43259 L 719.47111,495.43259 L 719.47111,353.16314 L 749.04761,353.16314 L 774.46011,293.58229 L 574.18111,293.58229 z" />
                        <path d="M 793.51721,293.58229 L 766.88261,353.16314 L 794.73941,353.16314 L 730.47061,495.43259 L 943.07601,495.43259 L 914.73041,434.78671 L 832.37351,434.78671 L 888.34021,293.58229 L 793.51721,293.58229 z" />
                        <path d="M 95.936814,373.64323 L 95.936814,293.58229 L 157.47313,293.58229 L 216.83573,383.61266 L 216.83573,293.58229 L 280.86877,293.58229 C 366.70884,293.58229 395.52589,352.62189 395.52589,352.62189 L 395.52589,293.58229 L 493.88448,293.58229 C 522.18651,293.58229 556.45091,302.18987 564.53471,345.11426 C 572.40021,386.89506 532.76701,401.32541 532.76701,401.32541 L 574.18111,459.073 L 574.18111,365.33247 L 639.60221,365.33247 L 639.60221,495.43259 L 515.44711,495.43259 L 462.12549,400.55719 L 462.12549,495.43259 L 395.52589,495.43259 L 395.52589,435.58985 C 359.52422,501.22918 290.03506,495.43259 290.03506,495.43259 L 290.03506,435.20574 C 290.03506,435.20574 336.42518,433.66057 336.42518,392.08929 C 336.42518,350.50928 280.41482,353.39884 280.41482,353.39884 L 280.41482,495.43259 L 214.19934,495.43259 L 160.10953,405.75142 L 160.10953,493.69536 L 154.52246,493.69536 L 95.936814,373.64323 z M 461.78503,386.40619 C 472.66235,390.92822 500.14375,386.88633 500.14375,365.90863 C 500.14375,344.92221 461.78503,350.70133 461.78503,350.70133 L 461.78503,386.40619 z" />
                    </g>
                </svg>
            </div>

            <!-- Blue Underline -->
            <div class="blue-divider"></div>

            <!-- 2. HERO PALLET NUMBER ROW -->
            <table class="hero-row">
                <tr>
                    <td style="width: 25%; text-align: right; vertical-align: middle;">
                        <span class="hero-dash"></span>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: middle;">
                        <span class="hero-number">Pallet #{{ $palletNum }}</span>
                    </td>
                    <td style="width: 25%; text-align: left; vertical-align: middle;">
                        <span class="hero-dash"></span>
                    </td>
                </tr>
            </table>

            <!-- 3. ANGLED BLUE BANNER: // I-COS // -->
            <div style="text-align: center; margin: 2pt auto 0 auto; width: 95%;">
                <svg viewBox="0 0 620 66" width="370pt" height="34pt" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="15,62 36,62 58,4 37,4" fill="#0047BA" />
                    <polygon points="49,62 571,62 593,4 71,4" fill="#0047BA" />
                    <polygon points="584,62 605,62 627,4 606,4" fill="#0047BA" />
                    <text x="325" y="47" font-family="Arial, Helvetica, sans-serif" font-weight="900" font-size="38" fill="#ffffff" text-anchor="middle" letter-spacing="2.5px">{{ $catCode }}</text>
                </svg>
            </div>

            <!-- 4. CATEGORY SUBTITLE -->
            <div class="category-title">
                {{ $catTitle }}
            </div>

            <!-- 5. WARNING CONFIRMATION ROW -->
            <table class="warning-table">
                <tr>
                    <td style="width: 44pt; vertical-align: middle; text-align: left;">
                        <!-- Blue Warning Triangle with White Inset -->
                        <svg viewBox="0 0 54 48" width="40pt" height="34pt" xmlns="http://www.w3.org/2000/svg">
                            <path d="M 27 3 L 51 43 C 51.8 44.5 50.8 46 49 46 L 5 46 C 3.2 46 2.2 44.5 3 43 L 27 3 Z" fill="#0047BA" stroke="#0047BA" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M 27 8 L 47 42 L 7 42 Z" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round" fill="#0047BA"/>
                            <rect x="25.5" y="16" width="3" height="13" rx="1.5" fill="#ffffff"/>
                            <circle cx="27" cy="35" r="2" fill="#ffffff"/>
                        </svg>
                    </td>
                    <td style="vertical-align: middle; padding-left: 6pt;">
                        <div class="warning-box">
                            Dont Open Without Confirmation
                        </div>
                    </td>
                </tr>
            </table>

        </div>

        <!-- 6. SOLID BLUE FOOTER BAR -->
        <div class="footer-bar">
            <table class="footer-table">
                <tr>
                    <!-- Left: Authentic ANDRITZ "A" + | + Andritz For The Change -->
                    <td style="vertical-align: middle; text-align: left;">
                        <table style="border-collapse: collapse;">
                            <tr>
                                <td style="vertical-align: middle; padding-right: 8pt;">
                                    <svg viewBox="0 0 264 202" width="24pt" height="18pt" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                        <g transform="translate(120, -292)">
                                            <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                                        </g>
                                    </svg>
                                </td>
                                <td style="vertical-align: middle; padding-right: 8pt;">
                                    <div style="width: 1.5pt; height: 18pt; background-color: #ffffff;"></div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="phone-text">Andritz For The Change</span>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- Right: 4 White Outline Handling Marks -->
                    <td style="vertical-align: middle; text-align: right;">
                        <!-- Icon 1: This Way Up -->
                        <div class="icon-box">
                            <svg viewBox="0 0 28 28" width="20pt" height="20pt" fill="none" stroke="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                <line x1="4" y1="23" x2="24" y2="23" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M9 20 L9 9 M6 11 L9 5 L12 11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19 20 L19 9 M16 11 L19 5 L22 11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <!-- Icon 2: Fragile Wine Glass with Crack -->
                        <div class="icon-box">
                            <svg viewBox="0 0 28 28" width="20pt" height="20pt" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 5 L21 5 C21 13 16 15 15 15 L15 21 L19 21 L19 23 L9 23 L9 21 L13 21 L13 15 C12 15 7 13 7 5 Z" fill="#ffffff"/>
                                <path d="M12 5 L10 9 L13 11 L11 14" stroke="#0047BA" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            </svg>
                        </div>

                        <!-- Icon 3: Keep Dry / Umbrella -->
                        <div class="icon-box">
                            <svg viewBox="0 0 28 28" width="20pt" height="20pt" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 16 C5 10 9 6.5 14 6.5 C19 6.5 23 10 23 16 C23 16 20 14.5 17 15 C14.5 15.5 13.5 15.5 11 15 C8.5 14.5 5 16 5 16 Z" fill="#ffffff"/>
                                <path d="M14 7 L14 20 C14 22 12.5 23 11 23 C9.5 23 9 22 9 21" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                                <line x1="18" y1="3.5" x2="17" y2="5.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                                <line x1="21" y1="4.5" x2="20" y2="6.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                                <line x1="23" y1="6.5" x2="22" y2="8.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <!-- Icon 4: Do Not Clamp / Package Care -->
                        <div class="icon-box">
                            <svg viewBox="0 0 28 28" width="20pt" height="20pt" fill="none" stroke="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                <rect x="4" y="4" width="20" height="20" rx="1.5" stroke-width="1.6"/>
                                <line x1="5" y1="5" x2="23" y2="23" stroke-width="1.6"/>
                                <line x1="23" y1="5" x2="5" y2="23" stroke-width="1.6"/>
                                <polygon points="14,7 21,14 14,21 7,14" stroke-width="1.4" fill="#0047BA"/>
                            </svg>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
@endforeach

</body>
</html>
