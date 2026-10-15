<?php
declare(strict_types=1);

namespace App\Http;

use App\View\SmartyView;

class Router
{
    private array $routes = [];

    public function __construct(
        private readonly SmartyView $view
    )
    {
    }

    public function get(string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method' => 'GET',
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method || !preg_match($route['pattern'], $request->path, $matches)) {
                continue;
            }

            $parameters = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            ($route['handler'])($request, $parameters);
            return;
        }

        http_response_code(404);
        $this->view->render('404.tpl');
    }
}
