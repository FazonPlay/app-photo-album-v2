<?php

/**
 * Get total number of users in the system
 *
 * @param PDO $pdo Database connection
 * @return int Total user count
 */
function getTotalUsers(PDO $pdo): int {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    } catch (PDOException $e) {
        error_log("Error getting user count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get total number of albums in the system
 * @param PDO $pdo Database connection
 * @return int Total album count
 */
function getTotalAlbums(PDO $pdo): int {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM albums");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    } catch (PDOException $e) {
        error_log("Error getting albums count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get total number of photos in the system
 * @param PDO $pdo Database connection
 * @return int Total photo count
 */
function getTotalPhotos(PDO $pdo): int {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM photos");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    } catch (PDOException $e) {
        error_log("Error getting photos count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Calculate total disk usage of all photos
 * @param PDO $pdo Database connection
 * @return string Formatted disk usage
 */
function getDiskUsage(PDO $pdo): string {
    try {
        $stmt = $pdo->prepare("SELECT SUM(file_size) as total_size FROM photos");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalSizeBytes = $result['total_size'] ?? 0;

        // Convert bytes to MB
        return number_format($totalSizeBytes / (1024 * 1024), 2) . ' MB';
    } catch (PDOException $e) {
        error_log("Error calculating disk usage: " . $e->getMessage());
        return '0 MB';
    }
}

/**
 * Get recent users for admin dashboard
 * @param PDO $pdo Database connection
 * @param int $limit Number of users to return
 * @return array List of recent users
 */
function getRecentUsers(PDO $pdo, int $limit = 5): array {
    $query = "SELECT user_id as id, username, email, registration_date, is_active 
              FROM users 
              ORDER BY registration_date DESC 
              LIMIT :limit";

    $statement = $pdo->prepare($query);
    $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    $users = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $user = new stdClass();
        $user->id = $row['id'];
        $user->username = $row['username'];
        $user->email = $row['email'];
        $user->registrationDate = date('M j, Y', strtotime($row['registration_date']));
        $user->isActive = (bool)$row['is_active'];

        $users[] = $user;
    }

    return $users;
}