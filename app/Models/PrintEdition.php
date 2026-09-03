<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintEdition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['issue_number', 'release_date', 'magcloud_url', 'cover_media_id', 'is_current', 'sort_order'];

    protected function casts(): array
    {
        return ['release_date' => 'date', 'is_current' => 'boolean'];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PrintEditionTranslation::class);
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }
}
