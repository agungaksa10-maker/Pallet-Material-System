<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PalletComponent extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'pallet_sticker_id',
        'component_name',
        'quantity',
        'batch_no',
        'kolom',
        'tingkat',
        'notes',
    ];

    /**
     * @return BelongsTo<PalletSticker, $this>
     */
    public function palletSticker(): BelongsTo
    {
        return $this->belongsTo(PalletSticker::class, 'pallet_sticker_id');
    }
}
