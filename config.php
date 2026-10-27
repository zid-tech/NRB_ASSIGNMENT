<?php
declare(strict_types=1);
session_start();

define('APP_NAME', 'NRB National ID Registration');
define('BASE_URL', getenv('BASE_URL') ?: '/nrb_registration_system'); // Change if your folder name differs or the app is installed at web root.

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'nrb_registration';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check database settings and import database/schema.sql.');
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(419); exit('Invalid form token. Refresh and try again.');
        }
    }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = [$type, $message]; }
function take_flash(): ?array {
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function require_login(): void {
    if (empty($_SESSION['user'])) { header('Location: ' . BASE_URL . '/login.php'); exit; }
}
function require_role(array $roles): void {
    require_login();
    if (!in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(403); exit('You do not have permission to perform this action.');
    }
}
function redirect(string $path): never { header('Location: ' . BASE_URL . $path); exit; }
