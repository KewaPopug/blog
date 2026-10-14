<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Service\CategoryService;
use App\View\SmartyView;

class CategoryController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly SmartyView $view
    ) {}

    public function index(Request $request, array $parameters = []): void
    {
        $this->view->render('index.tpl', [
            'categories' => $this->categoryService->categoriesWithRecentArticles(),
        ]);
    }
}
