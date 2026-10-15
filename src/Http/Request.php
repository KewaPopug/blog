<?php
declare(strict_types=1);

namespace App\Http;

class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        private readonly array $query,
    ) {}

    public static function fromGlobals(): self
    {
        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptDirectory = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($scriptDirectory !== '' && str_starts_with($uriPath, $scriptDirectory . '/')) {
            $uriPath = substr($uriPath, strlen($scriptDirectory));
        }

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            '/' . trim($uriPath, '/'),
            $_GET,
        );
    }

    public function queryString(string $key, string $default = ''): string
    {
        $value = $this->query[$key] ?? $default;
        return is_string($value) ? $value : $default;
    }

    public function queryInt(string $key, int $default = 1): int
    {
        $value = filter_var($this->query[$key] ?? null, FILTER_VALIDATE_INT);
        return $value === false || $value === null ? $default : (int) $value;
    }
}
