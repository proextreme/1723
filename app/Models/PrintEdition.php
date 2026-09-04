<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\PrintEditionFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintEdition extends Model
{
    /** @use HasFactory<PrintEditionFactory> */
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['issue_number', 'release_date', 'magcloud_url', 'cover_media_id', 'is_current', 'sort_order'];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    /**
     * The single edition currently on the shelf.
     *
     * @param  Builder<PrintEdition>  $query
     */
    #[Scope]
    protected function current(Builder $query): void
    {
        $query->where('is_current', true);
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }
}
