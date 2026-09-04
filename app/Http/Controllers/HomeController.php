<?php

namespace App\Http\Controllers;

use App\Enums\HomeGallerySection;
use App\Models\Article;
use App\Models\HomeGalleryImage;
use App\Support\Content\HomeContent;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(HomeContent $home): View
    {
        $gallery = HomeGalleryImage::query()->forSection(HomeGallerySection::Statement)->ordered()->get();
        $coverImages = HomeGalleryImage::query()->forSection(HomeGallerySection::Covers)->ordered()->get();

        $articles = Article::query()
            ->published()
            ->with(['translation', 'media' => fn ($q) => $q->wherePivot('is_featured', true)])
            ->latest('published_at')
            ->latest('id')
            ->take(6)
            ->get();

        return view('home', [
            'home' => $home,
            'gallery' => $gallery,
            'coverImages' => $coverImages,
            'articles' => $articles,
            // Front Covers is a slider, so it can hold more than fits on screen at once.
            'covers' => $articles,
        ]);
    }
}
