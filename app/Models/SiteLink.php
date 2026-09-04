<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\SiteLinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteLink extends Model
{
    /** @use HasFactory<SiteLinkFactory> */
    use Auditable, HasFactory;

    protected $fillable = ['key', 'label', 'url', 'target', 'media_id', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
