<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Database\Factories\HomeGalleryImageFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A single tile in the home page's editorial image grid.
 */
class HomeGalleryImage extends Model
{
    /** @use HasFactory<HomeGalleryImageFactory> */
    use Auditable, HasFactory;

    protected $fillable = ['disk', 'path', 'alt', 'url', 'sort_order'];

    /**
     * @param  Builder<HomeGalleryImage>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function publicUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
