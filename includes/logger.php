<?php
/**
 * Log system activity to file
 *
 * @param string $action The action performed (login, logout, create, update, delete, etc)
 * @param string|null $entityType Type of entity (user, album, photo, etc)
 * @param int|null $entityId ID of the entity
 * @param string|null $details Additional details about the action
 * @return bool Success status
 */
function logActivity(string $action, ?string $entityType = null, ?int $entityId = null, ?string $details = null): bool {
    // Create logs directory if it doesn't exist
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/system_activity.log';

    // Get current timestamp
    $timestamp = date('Y-m-d H:i:s');

    // Get user information
    $userId = $_SESSION['user_id'] ?? 0;
    $username = $_SESSION['username'] ?? 'Guest';

    // Get IP address and user agent
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Build log entry
    $logEntry = sprintf(
        "[%s] User ID: %d | Username: %s | Action: %s | Entity: %s | ID: %s | Details: %s | IP: %s\n",
        $timestamp,
        $userId,
        $username,
        $action,
        $entityType ?? 'N/A',
        $entityId ?? 'N/A',
        $details ?? 'N/A',
        $ipAddress
    );

    // Write to log file (append)
    return (bool)file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Log user login
 */
function logLogin(int $userId, string $username, ?string $role = null, bool $success = true, ?string $failReason = null): bool {
    // Get role from parameter, session, or default to 'user'
    $role = $role ?? $_SESSION['role'] ?? 'user';

    // Custom role description based on your database schema
    $roleInfo = match($role) {
        'admin' => 'Administrator',
        'premium' => 'Premium user',
        'lifetime' => 'Lifetime member',
        default => 'Regular user'
    };

    // Build details message
    if ($success) {
        $details = "$roleInfo '$username' logged in";
    } else {
        $details = "Failed login attempt for '$username'" . ($failReason ? ": $failReason" : "");
    }

    // Log the activity with the role as the entity type
    return logActivity($success ? 'login' : 'login_failed', $role, $userId, $details);
}

/**
 * Log user logout
 */
function logLogout(): bool {
    $role = $_SESSION['role'] ?? 'user';
    return logActivity('logout', $role, $_SESSION['user_id'] ?? null, 'User logged out');
}

/**
 * Log user registration
 */
function logRegistration(int $userId, string $username): bool {
    return logActivity('register', 'user', $userId, "New user registered: $username");
}

/**
 * Log entity creation
 */
function logCreation(string $entityType, int $entityId, string $name): bool {
    return logActivity('create', $entityType, $entityId, "$entityType '$name' was created");
}

/**
 * Log entity update
 */
function logUpdate(string $entityType, int $entityId, string $name): bool {
    return logActivity('update', $entityType, $entityId, "$entityType '$name' was updated");
}

/**
 * Log entity deletion
 */
function logDeletion(string $entityType, int $entityId, string $name): bool {
    return logActivity('delete', $entityType, $entityId, "$entityType '$name' was deleted");
}