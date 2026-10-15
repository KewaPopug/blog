<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Service\CategoryService;
use App\View\SmartyView;

final class CategoryController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly SmartyView $view
    ) {}

    public function index(Request $request, array $parameters): void
    {
        $data = $this->categoryService->getCategoryPage(
            $parameters['slug'],
            $request->queryString('sort', 'date'),
            $request->queryInt('page'),
        );

        if ($data === null) {
            http_response_code(404);
            $this->view->render('404.tpl');
            return;
        }

        $this->view->render('category/index.tpl', $data);
    }
}
