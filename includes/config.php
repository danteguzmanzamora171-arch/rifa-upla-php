<?php
// Configuración compartida por web y API.
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
const APP_USER = 'userupla';
const APP_PASSWORD = 'upla123'; // Solo para la evaluación; en producción usar password_hash.
const PRICE = 2000;
function require_web_login(): void {
    if (empty($_SESSION['web_auth'])) { header('Location: index.php'); exit; }
}
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
    return $_SESSION['csrf'];
}
function valid_csrf(): bool {return isset($_POST['csrf']) && hash_equals(csrf_token(), (string)$_POST['csrf']); }
function redirect(string $path): void {header('Location: '.$path); exit; }
