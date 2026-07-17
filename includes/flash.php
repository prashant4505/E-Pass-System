<?php
declare(strict_types=1);

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function getFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function renderFlashes(): void
{
    foreach (getFlashes() as $item) {
        printf('<div class="flash flash-%s">%s</div>', e($item['type']), e($item['message']));
    }
}
