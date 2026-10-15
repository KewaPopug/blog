<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Service\ArticleService;
use App\View\SmartyView;

class ArticleController
{
    public function __construct(
        readonly private ArticleService $articleService,
        readonly private SmartyView $view
    ) {}

    public function index(Request $request, array $parameters): void
    {
        $data = $this->articleService->getArticlePage($parameters['slug']);
        if ($data === null) {
            http_response_code(404);
            $this->view->render('404.tpl');
            return;
        }

        $this->view->render('article/index.tpl', $data);
    }
}
