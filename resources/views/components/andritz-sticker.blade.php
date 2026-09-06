@props([
    'palletNumber' => 1,
    'category' => 'Consumable',
    'categoryCode' => null,
    'idPrefix' => 'preview',
])

@php
    $catCode = $categoryCode ?? 'I-COS';
    $catTitle = ($category === 'Dressing') ? 'Dressing Material' : 'Consumable Material';
@endphp

<div class="andritz-sticker-card w-full max-w-[480px] mx-auto bg-white border-[7px] border-[#0047BA] rounded-[24px] shadow-2xl overflow-hidden select-none font-sans relative flex flex-col justify-between" style="aspect-ratio: 430 / 345;">
    
    <!-- TOP & BODY CONTENT -->
    <div class="px-5 sm:px-6 pt-5 pb-2 flex flex-col justify-between flex-1">
        
        <!-- 1. OFFICIAL ANDRITZ LOGO HEADER -->
        <div class="w-full flex justify-center items-center">
            <svg viewBox="0 0 1065 210" class="w-[82%] max-w-[340px] h-auto" xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(120, -290)" fill="#0047BA">
                    <!-- A -->
                    <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                    <!-- T -->
                    <path d="M 574.18111,293.58229 L 574.18111,352.9187 L 654.22461,352.9187 L 654.22461,495.43259 L 719.47111,495.43259 L 719.47111,353.16314 L 749.04761,353.16314 L 774.46011,293.58229 L 574.18111,293.58229 z" />
                    <!-- Z -->
                    <path d="M 793.51721,293.58229 L 766.88261,353.16314 L 794.73941,353.16314 L 730.47061,495.43259 L 943.07601,495.43259 L 914.73041,434.78671 L 832.37351,434.78671 L 888.34021,293.58229 L 793.51721,293.58229 z" />
                    <!-- NDRI -->
                    <path d="M 95.936814,373.64323 L 95.936814,293.58229 L 157.47313,293.58229 L 216.83573,383.61266 L 216.83573,293.58229 L 280.86877,293.58229 C 366.70884,293.58229 395.52589,352.62189 395.52589,352.62189 L 395.52589,293.58229 L 493.88448,293.58229 C 522.18651,293.58229 556.45091,302.18987 564.53471,345.11426 C 572.40021,386.89506 532.76701,401.32541 532.76701,401.32541 L 574.18111,459.073 L 574.18111,365.33247 L 639.60221,365.33247 L 639.60221,495.43259 L 515.44711,495.43259 L 462.12549,400.55719 L 462.12549,495.43259 L 395.52589,495.43259 L 395.52589,435.58985 C 359.52422,501.22918 290.03506,495.43259 290.03506,495.43259 L 290.03506,435.20574 C 290.03506,435.20574 336.42518,433.66057 336.42518,392.08929 C 336.42518,350.50928 280.41482,353.39884 280.41482,353.39884 L 280.41482,495.43259 L 214.19934,495.43259 L 160.10953,405.75142 L 160.10953,493.69536 L 154.52246,493.69536 L 95.936814,373.64323 z M 461.78503,386.40619 C 472.66235,390.92822 500.14375,386.88633 500.14375,365.90863 C 500.14375,344.92221 461.78503,350.70133 461.78503,350.70133 L 461.78503,386.40619 z" />
                </g>
            </svg>
        </div>

        <!-- Solid Blue Horizontal Divider -->
        <div class="w-full h-[3.5px] bg-[#0047BA] my-1 sm:my-1.5"></div>

        <!-- 2. HERO ROW: — Pallet #1 — -->
        <div class="flex items-center justify-center gap-3 sm:gap-4 my-0.5">
            <span class="w-12 sm:w-16 h-[5.5px] bg-[#0047BA] rounded-xs shrink-0"></span>
            <span class="text-3xl sm:text-[38px] font-black text-black tracking-tight text-center font-sans whitespace-nowrap leading-none">
                Pallet #<span id="{{ $idPrefix }}PalletNumber">{{ $palletNumber }}</span>
            </span>
            <span class="w-12 sm:w-16 h-[5.5px] bg-[#0047BA] rounded-xs shrink-0"></span>
        </div>

        <!-- 3. ANGLED BLUE BANNER: // I-COS // -->
        <div class="w-full my-0.5">
            <svg viewBox="0 0 620 66" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                <!-- Left Slash -->
                <polygon points="15,62 36,62 58,4 37,4" fill="#0047BA" />
                <!-- Center Banner Polygon with Slanted Ends -->
                <polygon points="49,62 571,62 593,4 71,4" fill="#0047BA" />
                <!-- Right Slash -->
                <polygon points="584,62 605,62 627,4 606,4" fill="#0047BA" />
                <!-- Category Code Text -->
                <text x="325" y="47" font-family="'Plus Jakarta Sans', Arial, Helvetica, sans-serif" font-weight="900" font-size="38" fill="#ffffff" text-anchor="middle" letter-spacing="2.5px" id="{{ $idPrefix }}CategoryCode">{{ $catCode }}</text>
            </svg>
        </div>

        <!-- 4. CATEGORY SUBTITLE -->
        <div class="text-center font-black text-black text-xl sm:text-2xl tracking-tight leading-tight" id="{{ $idPrefix }}CategoryTitle">
            {{ $catTitle }}
        </div>

        <!-- 5. WARNING CONFIRMATION ROW -->
        <div class="flex items-center gap-2.5 sm:gap-3.5 my-1">
            <!-- Warning Blue Triangle with White Inset & Exclamation Mark -->
            <div class="shrink-0 w-12 h-10 sm:w-13 sm:h-11 flex items-center justify-center">
                <svg viewBox="0 0 54 48" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <!-- Outer Blue Triangle with Rounded Tips -->
                    <path d="M 27 3 L 51 43 C 51.8 44.5 50.8 46 49 46 L 5 46 C 3.2 46 2.2 44.5 3 43 L 27 3 Z" fill="#0047BA" stroke="#0047BA" stroke-width="2" stroke-linejoin="round"/>
                    <!-- Inner White Outline -->
                    <path d="M 27 8 L 47 42 L 7 42 Z" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round" fill="#0047BA"/>
                    <!-- Exclamation Mark -->
                    <rect x="25.5" y="16" width="3" height="13" rx="1.5" fill="#ffffff"/>
                    <circle cx="27" cy="35" r="2" fill="#ffffff"/>
                </svg>
            </div>

            <!-- Confirmation Border Box -->
            <div class="flex-1 border-[2.8px] border-[#0047BA] rounded-xl py-2 px-3 sm:py-2.5 sm:px-4 text-center font-black text-black text-xs sm:text-[15px] tracking-tight bg-white">
                Dont Open Without Confirmation
            </div>
        </div>

    </div>

    <!-- 6. SOLID BLUE FOOTER BAR -->
    <div class="bg-[#0047BA] text-white px-4 sm:px-5 py-2 sm:py-2.5 flex items-center justify-between mt-1">
        
        <!-- Left: Official ANDRITZ "A" Symbol + | + Tagline -->
        <div class="flex items-center min-w-0">
            <!-- Authentic ANDRITZ "A" Vector Icon -->
            <svg viewBox="0 0 264 202" class="h-6 sm:h-7 w-auto shrink-0" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(120, -292)">
                    <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                </g>
            </svg>

            <!-- Vertical Divider -->
            <div class="w-[2px] h-5 sm:h-6 bg-white mx-2.5 sm:mx-3 shrink-0"></div>

            <!-- Tagline -->
            <span class="font-black text-xs sm:text-base tracking-wide whitespace-nowrap leading-none">
                Andritz For The Change
            </span>
        </div>

        <!-- Right: 4 Logistics Handling Marks -->
        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
            <!-- Icon 1: This Way Up -->
            <div class="w-6 h-6 sm:w-7 sm:h-7 border-[1.5px] border-white rounded-[4px] flex items-center justify-center p-0.5" title="This Way Up">
                <svg viewBox="0 0 28 28" class="w-full h-full" fill="none" stroke="#ffffff" xmlns="http://www.w3.org/2000/svg">
                    <line x1="4" y1="23" x2="24" y2="23" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M9 20 L9 9 M6 11 L9 5 L12 11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19 20 L19 9 M16 11 L19 5 L22 11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Icon 2: Fragile Wine Glass with Crack -->
            <div class="w-6 h-6 sm:w-7 sm:h-7 border-[1.5px] border-white rounded-[4px] flex items-center justify-center p-0.5" title="Fragile">
                <svg viewBox="0 0 28 28" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 5 L21 5 C21 13 16 15 15 15 L15 21 L19 21 L19 23 L9 23 L9 21 L13 21 L13 15 C12 15 7 13 7 5 Z" fill="#ffffff"/>
                    <path d="M12 5 L10 9 L13 11 L11 14" stroke="#0047BA" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>

            <!-- Icon 3: Keep Dry / Umbrella -->
            <div class="w-6 h-6 sm:w-7 sm:h-7 border-[1.5px] border-white rounded-[4px] flex items-center justify-center p-0.5" title="Keep Dry">
                <svg viewBox="0 0 28 28" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <!-- Umbrella Canopy -->
                    <path d="M5 16 C5 10 9 6.5 14 6.5 C19 6.5 23 10 23 16 C23 16 20 14.5 17 15 C14.5 15.5 13.5 15.5 11 15 C8.5 14.5 5 16 5 16 Z" fill="#ffffff"/>
                    <!-- Handle -->
                    <path d="M14 7 L14 20 C14 22 12.5 23 11 23 C9.5 23 9 22 9 21" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                    <!-- Raindrops -->
                    <line x1="18" y1="3.5" x2="17" y2="5.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="21" y1="4.5" x2="20" y2="6.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="23" y1="6.5" x2="22" y2="8.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- Icon 4: Protect / Package Care -->
            <div class="w-6 h-6 sm:w-7 sm:h-7 border-[1.5px] border-white rounded-[4px] flex items-center justify-center p-0.5" title="Protect">
                <svg viewBox="0 0 28 28" class="w-full h-full" fill="none" stroke="#ffffff" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="20" height="20" rx="1.5" stroke-width="1.6"/>
                    <line x1="5" y1="5" x2="23" y2="23" stroke-width="1.6"/>
                    <line x1="23" y1="5" x2="5" y2="23" stroke-width="1.6"/>
                    <polygon points="14,7 21,14 14,21 7,14" stroke-width="1.4" fill="#0047BA"/>
                </svg>
            </div>
        </div>

    </div>

</div>

