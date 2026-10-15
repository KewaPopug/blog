<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\ArticleRepository;

class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository
    ) {}

    public function getArticlePage(string $slug): ?array
    {
        $article = $this->articleRepository->findBySlug($slug);
        if (!$article) {
            return null;
        }

        $this->articleRepository->incrementViews((int) $article['id']);
        $article['views'] = (int) $article['views'] + 1;

        return [
            'article' => $article,
            'related' => $this->articleRepository->findRelated((int) $article['id']),
        ];
    }
}
