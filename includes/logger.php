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
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/system_activity.log';

    $timestamp = date('Y-m-d H:i:s');

    $userId = $_SESSION['user_id'] ?? 0;
    $username = $_SESSION['username'] ?? 'Guest';

    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

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

    return (bool)file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Log user login
 */
function logLogin(int $userId, string $username, ?string $role = null, bool $success = true, ?string $failReason = null): bool {
    $role = $role ?? $_SESSION['role'] ?? 'user';

    $roleInfo = match($role) {
        'admin' => 'Administrator',
        'premium' => 'Premium user',
        'lifetime' => 'Lifetime member',
        default => 'Regular user'
    };

    if ($success) {
        $details = "$roleInfo '$username' logged in";
    } else {
        $details = "Failed login attempt for '$username'" . ($failReason ? ": $failReason" : "");
    }

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
 * Fixed to properly handle new user registration with correct user ID
 */
function logRegistration(int $userId, string $username, string $email = '', string $role = 'user'): bool {
    $details = "New user registered: $username";
    if (!empty($email)) {
        $details .= " (Email: $email)";
    }
    $details .= " | Role: $role";

    return logActivity('register', 'user', $userId, $details);
}

/**
 * Log entity creation with enhanced details
 */
function logCreation(string $entityType, int $entityId, string $name, array $additionalInfo = []): bool {
    $details = "$entityType '$name' was created";

    // Add additional context based on entity type
    if (!empty($additionalInfo)) {
        switch ($entityType) {
            case 'album':
                if (isset($additionalInfo['visibility'])) {
                    $details .= " | Visibility: " . $additionalInfo['visibility'];
                }
                if (!empty($additionalInfo['description'])) {
                    $details .= " | Description: " . substr($additionalInfo['description'], 0, 50) . "...";
                }
                break;
            case 'photo':
                if (isset($additionalInfo['album_name'])) {
                    $details .= " | Album: " . $additionalInfo['album_name'];
                }
                if (isset($additionalInfo['file_size'])) {
                    $details .= " | Size: " . formatBytes($additionalInfo['file_size']);
                }
                break;
            case 'user':
                if (isset($additionalInfo['role'])) {
                    $details .= " | Role: " . $additionalInfo['role'];
                }
                break;
        }
    }

    return logActivity('create', $entityType, $entityId, $details);
}

/**
 * Log entity update with detailed change information
 */
function logUpdate(string $entityType, int $entityId, string $name, array $changes = [], array $oldValues = []): bool {
    $details = "$entityType '$name' was updated";

    // Add specific change details
    if (!empty($changes)) {
        $changeDetails = [];
        foreach ($changes as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? 'N/A';

            // Handle sensitive fields
            if (in_array($field, ['password', 'password_hash'])) {
                $changeDetails[] = "$field: [CHANGED]";
            } else {
                // Truncate long values
                $oldValueStr = is_string($oldValue) ? substr($oldValue, 0, 30) : $oldValue;
                $newValueStr = is_string($newValue) ? substr($newValue, 0, 30) : $newValue;

                if (strlen($oldValue) > 30) $oldValueStr .= "...";
                if (strlen($newValue) > 30) $newValueStr .= "...";

                $changeDetails[] = "$field: '$oldValueStr' → '$newValueStr'";
            }
        }

        if (!empty($changeDetails)) {
            $details .= " | Changes: " . implode(', ', $changeDetails);
        }
    }

    return logActivity('update', $entityType, $entityId, $details);
}

/**
 * Log entity deletion with proper name resolution
 */
function logDeletion(string $entityType, int $entityId, string $name = '', array $additionalInfo = []): bool {
    // If name is empty, try to get it from additional info or use generic name
    if (empty($name)) {
        $name = $additionalInfo['name'] ?? $additionalInfo['title'] ?? $additionalInfo['username'] ?? 'Unknown';
    }

    $details = "$entityType '$name' was deleted";

    // Add context based on entity type
    switch ($entityType) {
        case 'user':
            if (isset($additionalInfo['role'])) {
                $roleDisplay = match($additionalInfo['role']) {
                    'admin' => 'Administrator',
                    'premium' => 'Premium user',
                    'lifetime' => 'Lifetime member',
                    default => 'Regular user'
                };
                $details = "$roleDisplay '$name' was deleted";
            }
            if (isset($additionalInfo['email'])) {
                $details .= " | Email: " . $additionalInfo['email'];
            }
            break;

        case 'album':
            if (isset($additionalInfo['photo_count'])) {
                $details .= " | Photos deleted: " . $additionalInfo['photo_count'];
            }
            if (isset($additionalInfo['visibility'])) {
                $details .= " | Was " . $additionalInfo['visibility'];
            }
            break;

        case 'photo':
            if (isset($additionalInfo['album_name'])) {
                $details .= " | From album: " . $additionalInfo['album_name'];
            }
            if (isset($additionalInfo['file_path'])) {
                $filename = basename($additionalInfo['file_path']);
                $details .= " | File: $filename";
            }
            break;
    }

    return logActivity('delete', $entityType, $entityId, $details);
}

/**
 * Log album access changes (invitations, permissions)
 */
function logAlbumAccess(string $action, int $albumId, string $albumName, int $targetUserId, string $targetUsername, string $permission, array $additionalInfo = []): bool {
    $details = match($action) {
        'invite_sent' => "Invitation sent to '$targetUsername' for album '$albumName' | Permission: $permission",
        'invite_accepted' => "Invitation accepted by '$targetUsername' for album '$albumName' | Permission: $permission",
        'invite_declined' => "Invitation declined by '$targetUsername' for album '$albumName'",
        'access_granted' => "Access granted to '$targetUsername' for album '$albumName' | Permission: $permission",
        'access_revoked' => "Access revoked from '$targetUsername' for album '$albumName'",
        'permission_changed' => "Permission changed for '$targetUsername' in album '$albumName' | New permission: $permission",
        default => "Album access action '$action' for '$targetUsername' in album '$albumName'"
    };

    if (!empty($additionalInfo['message'])) {
        $details .= " | Message: " . substr($additionalInfo['message'], 0, 50) . "...";
    }

    return logActivity($action, 'album_access', $albumId, $details);
}
