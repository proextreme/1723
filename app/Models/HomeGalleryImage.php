<?php

namespace App\Models;

use App\Enums\HomeGallerySection;
use App\Models\Concerns\Auditable;
use Database\Factories\HomeGalleryImageFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A single tile in one of the home page's image grids — the "Work gains
 * value" editorial grid or the Front Covers slider, per `section`.
 */
class HomeGalleryImage extends Model
{
    /** @use HasFactory<HomeGalleryImageFactory> */
    use Auditable, HasFactory;

    protected $fillable = ['section', 'disk', 'path', 'alt', 'url', 'sort_order'];

    protected function casts(): array
    {
        return [
            'section' => HomeGallerySection::class,
        ];
    }

    /**
     * @param  Builder<HomeGalleryImage>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @param  Builder<HomeGalleryImage>  $query
     */
    #[Scope]
    protected function forSection(Builder $query, HomeGallerySection $section): void
    {
        $query->where('section', $section);
    }

    public function publicUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
