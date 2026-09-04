<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Adds locale-aware translation relations to a content model whose translations
 * live in a sibling `{Model}Translation` table.
 */
trait HasTranslations
{
    /**
     * All translation rows for this record.
     */
    public function translations(): HasMany
    {
        return $this->hasMany($this->translationModel());
    }

    /**
     * The single translation for the active locale, falling back to the
     * application's fallback locale. Eager-load with `->with('translation')`
     * on list queries to avoid an N+1.
     */
    public function translation(): HasOne
    {
        $locale = app()->getLocale();
        $locales = array_values(array_unique([$locale, config('app.fallback_locale')]));

        return $this->hasOne($this->translationModel())
            ->whereIn('locale', $locales)
            ->orderByRaw('(locale = ?) desc', [$locale]);
    }

    /**
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     */
    public function scopeWithTranslation(Builder $query): void
    {
        $query->with('translation');
    }

    /**
     * Fully-qualified class name of the translation model.
     */
    protected function translationModel(): string
    {
        return static::class.'Translation';
    }
}
