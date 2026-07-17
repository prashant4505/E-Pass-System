<?php
declare(strict_types=1);

/** Ensure an admin is logged in; returns their row or redirects. */
function requireLogin(string $redirectTo = '../index.php'): array
{
    if (empty($_SESSION['uid'])) {
        redirect($redirectTo);
    }
    return currentAdmin($redirectTo);
}

function currentAdmin(string $redirectTo = '../index.php'): array
{
    global $pdo;
    static $admin = null;

    if ($admin !== null) {
        return $admin;
    }

    $stmt = $pdo->prepare("SELECT * FROM login WHERE ID = ? AND Status = 'active' LIMIT 1");
    $stmt->execute([$_SESSION['uid']]);
    $row = $stmt->fetch();

    if (!$row) {
        session_destroy();
        redirect($redirectTo);
    }

    $admin = $row;
    return $admin;
}

/** Ensure the logged-in admin has a given role, else bounce to the dashboard. */
function requireRole(string $role, string $dashboardPath = 'Dashboard.php'): array
{
    $admin = requireLogin();
    if ($admin['Role'] !== $role) {
        flash('error', 'You are not authorized to view that page.');
        redirect($dashboardPath);
    }
    return $admin;
}

/** Simple session-based login throttle: 5 failed attempts locks out for 60s. */
function loginIsLocked(): bool
{
    return !empty($_SESSION['login_locked_until']) && $_SESSION['login_locked_until'] > time();
}

function registerFailedLogin(): void
{
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_locked_until'] = time() + 60;
        $_SESSION['login_attempts'] = 0;
    }
}

function clearLoginThrottle(): void
{
    unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
}
