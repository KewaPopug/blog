<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

class CategoryService
{
    private const PAGE_SIZE = 6;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository  $articleRepository,
    ) {}

    public function getCategoryPage(string $slug, string $sort, int $requestedPage): ?array
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category) {
            return null;
        }

        $sort = $sort === 'views' ? 'views' : 'date';
        $totalArticles = $this->articleRepository->countByCategory((int) $category['id']);
        $totalPages = max(1, (int) ceil($totalArticles / self::PAGE_SIZE));
        $page = min(max(1, $requestedPage), $totalPages);
        $articles = $this->articleRepository->findByCategory(
            (int) $category['id'],
            $sort,
            self::PAGE_SIZE,
            ($page - 1) * self::PAGE_SIZE,
        );

        return [
            'category' => $category,
            'articles' => $articles,
            'page' => $page,
            'totalPages' => $totalPages,
            'sort' => $sort,
        ];
    }

    public function categoriesWithRecentArticles(): array
    {
        return $this->categoryRepository->findAllWithRecentArticles();
    }
}
