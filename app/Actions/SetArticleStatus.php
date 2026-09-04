<?php

namespace App\Actions;

use App\Enums\ArticleStatus;
use App\Models\Article;
use DomainException;

/**
 * Move an article through the editorial workflow (draft → review → published,
 * and back). Publishing stamps `published_at` the first time; returning to a
 * pre-published state leaves the timestamp so a re-publish keeps the date.
 */
class SetArticleStatus
{
    public function handle(Article $article, ArticleStatus $target): Article
    {
        if ($article->status === $target) {
            return $article;
        }

        if (! $article->status->canTransitionTo($target)) {
            throw new DomainException(
                "An article cannot move from {$article->status->value} to {$target->value}.",
            );
        }

        $article->status = $target;

        if ($target === ArticleStatus::Published && $article->published_at === null) {
            $article->published_at = now();
        }

        $article->save();

        return $article;
    }
}
