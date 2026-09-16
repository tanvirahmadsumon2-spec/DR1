<?php
declare(strict_types=1);

use App\Core\Session;

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_field(): string
{
    $token = Session::getCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

function csrf_token(): string
{
    return Session::getCsrfToken();
}

function t(string $key, ?string $default = null): string
{
    static $translations = null;
    $lang = Session::get('lang', 'bn');

    if ($translations === null) {
        $file = __DIR__ . '/../../lang/' . $lang . '.php';
        if (file_exists($file)) {
            $translations = require $file;
        } else {
            $translations = [];
        }
    }

    return $translations[$key] ?? $default ?? $key;
}

function url(string $path = ''): string
{
    $base = rtrim(getenv('APP_URL') ?: '', '/');
    $cleanPath = '/' . ltrim($path, '/');
    return $base . $cleanPath;
}

function redirect(string $path): void
{
    header("Location: " . $path);
    exit;
}

function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function currentUser(): ?array
{
    return Session::get('user');
}

function authCheck(): bool
{
    return Session::has('user');
}

function hasRole(string ...$roles): bool
{
    $user = currentUser();
    if (!$user) return false;
    return in_array($user['role'] ?? '', $roles, true);
}
