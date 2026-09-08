<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PalletSticker extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'site',
        'category',
        'pallet_number',
        'pallet_code',
        'material_name',
        'batch_no',
        'quantity',
        'kolom',
        'tingkat',
        'notes',
        'user_id',
        'printed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pallet_number' => 'integer',
            'printed_at' => 'datetime',
        ];
    }

    /**
     * Components contained inside this pallet.
     *
     * @return HasMany<PalletComponent, $this>
     */
    public function components(): HasMany
    {
        return $this->hasMany(PalletComponent::class, 'pallet_sticker_id');
    }

    /**
     * Check if a pallet number is already used in a given site.
     */
    public static function isPalletUsed(string $site, int $palletNumber, ?int $ignoreId = null): bool
    {
        $query = self::where('site', $site)
            ->where('pallet_number', $palletNumber);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * Get list of used pallet numbers for a given site.
     *
     * @return array<int>
     */
    public static function getUsedPalletNumbers(string $site, ?int $ignoreId = null): array
    {
        $query = self::where('site', $site);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->pluck('pallet_number')->map(fn ($num) => (int) $num)->toArray();
    }

    /**
     * Get the next available sequential pallet number for a given site (1 to 500).
     */
    public static function getNextAvailablePalletNumber(string $site): int
    {
        $used = self::getUsedPalletNumbers($site);

        for ($i = 1; $i <= 500; $i++) {
            if (! in_array($i, $used, true)) {
                return $i;
            }
        }

        return 500;
    }

    /**
     * Generate standard pallet barcode/code: e.g. PLT-OKI2-DRS-001
     */
    public static function generateCode(string $site, string $category, int $palletNumber): string
    {
        $siteMap = [
            'OKI II' => 'OKI2',
            'IKPD' => 'IKPD',
            'IKPP' => 'IKPP',
            'TELL' => 'TELL',
            'ISC' => 'ISC',
        ];

        $siteCode = $siteMap[$site] ?? preg_replace('/[^A-Za-z0-9]/', '', strtoupper($site));
        $catMap = [
            'Dressing' => 'DRS',
            'Consumable' => 'CON',
        ];
        $catCode = $catMap[$category] ?? strtoupper(substr($category, 0, 3));
        $numCode = str_pad((string) $palletNumber, 3, '0', STR_PAD_LEFT);

        return "PLT-{$siteCode}-{$catCode}-{$numCode}";
    }
}
