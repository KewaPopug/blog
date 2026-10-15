<?php
declare(strict_types=1);

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Database\ConnectionFactory;
use App\Http\Request;
use App\Http\Router;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;
use App\View\SmartyView;

if (PHP_SAPI === 'cli-server') {
    $requestedFile = realpath(__DIR__ . (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/'));
    if ($requestedFile && str_starts_with($requestedFile, __DIR__ . DIRECTORY_SEPARATOR) && is_file($requestedFile)) {
        return false;
    }
}

require dirname(__DIR__) . '/vendor/autoload.php';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$view = new SmartyView(dirname(__DIR__) . '/views', $basePath);

try {
    $db = ConnectionFactory::createFromEnvironment();
    $categoryRepository = new CategoryRepository($db);
    $articleRepository = new ArticleRepository($db);
    $categoryService = new CategoryService($categoryRepository, $articleRepository);
    $articleService = new ArticleService($articleRepository);

    $homeController = new HomeController($categoryService, $view);
    $categoryController = new CategoryController($categoryService, $view);
    $articleController = new ArticleController($articleService, $view);

    $router = new Router($view);
    $router->get('#^/$#D', [$homeController, 'index']);
    $router->get('#^/category/(?P<slug>[a-z0-9-]+)/?$#D', [$categoryController, 'index']);
    $router->get('#^/article/(?P<slug>[a-z0-9-]+)/?$#D', [$articleController, 'index']);
    $router->dispatch(Request::fromGlobals());
} catch (Throwable $exception) {
    http_response_code(500);
    error_log((string)$exception);
    $view->render('500.tpl');
}
