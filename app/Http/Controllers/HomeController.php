<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\PrintEdition;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
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

        return view('home', [
            'articles' => $articles,
            'covers' => $articles->take(3),
            'printEditions' => $printEditions,
        ]);
    }
}
