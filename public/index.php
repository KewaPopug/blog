<?php
declare(strict_types=1);

use App\Controller\CategoryController;
use App\Database\ConnectionFactory;
use App\Http\Request;
use App\Http\Router;
use App\Repository\CategoryRepository;
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
    $categoryController = new CategoryController(
        new CategoryService($categoryRepository),
        $view,
    );

    $router = new Router();
    $router->get('#^/$#D', [$categoryController, 'index']);

    if (!$router->dispatch(Request::fromGlobals())) {
        http_response_code(404);
        $view->render('404.tpl');
    }
} catch (Throwable $exception) {
    http_response_code(500);
    error_log((string)$exception);
    $view->render('500.tpl');
}
