<?php

namespace App\Models;

use Database\Factories\MasterMaterialFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMaterial extends Model
{
    /** @use HasFactory<MasterMaterialFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'item_code',
        'name',
        'category',
        'default_unit',
        'specification',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<MasterMaterial>  $query
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<MasterMaterial>  $query
     */
    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if (empty($category) || $category === 'ALL') {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * @param  Builder<MasterMaterial>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $sub) use ($term) {
            $sub->where('item_code', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%")
                ->orWhere('specification', 'like', "%{$term}%");
        });
    }
}
