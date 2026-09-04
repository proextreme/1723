<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\Content\HomeContent;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(HomeContent $home): View
    {
        $articles = Article::query()
            ->published()
            ->with(['translation', 'media' => fn ($q) => $q->wherePivot('is_featured', true)])
            ->latest('published_at')
            ->latest('id')
            ->take(6)
            ->get();

        return view('home', [
            'home' => $home,
            'articles' => $articles,
            'covers' => $articles->take(3),
        ]);
    }
}
