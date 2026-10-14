<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\CategoryRepository;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {}

    public function categoriesWithRecentArticles(): array
    {
        return $this->categoryRepository->findAllWithRecentArticles();
    }
}
