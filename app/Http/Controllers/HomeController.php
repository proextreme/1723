<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Media;
use App\Models\PrintEdition;
use App\Support\Settings\SettingsRepository;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(SettingsRepository $settings): View
    {
        $articles = Article::query()
            ->published()
            ->with(['translation', 'media' => fn ($q) => $q->wherePivot('is_featured', true)])
            ->latest('published_at')
            ->latest('id')
            ->take(6)
            ->get();

        $printEditions = PrintEdition::query()
            ->with(['translation', 'coverMedia'])
            ->orderByDesc('is_current')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $printFeature = Media::query()->find($settings->get('home_print_media_id'))
            ?? $printEditions->firstWhere('is_current')?->coverMedia
            ?? $printEditions->first()?->coverMedia;

        return view('home', [
            'articles' => $articles,
            'covers' => $articles->take(3),
            'printFeature' => $printFeature,
        ]);
    }
}
