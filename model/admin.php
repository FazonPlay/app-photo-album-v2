<?php

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


function getDiskUsage(PDO $pdo): string {
    try {
        $stmt = $pdo->prepare("SELECT file_path, thumbnail_path FROM photos");
        $stmt->execute();
        $totalSizeBytes = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $filePath = __DIR__ . '/../' . $row['file_path'];
            if (file_exists($filePath)) {
                $totalSizeBytes += filesize($filePath);
            }
            if (!empty($row['thumbnail_path'])) {
                $thumbnailPath = __DIR__ . '/../' . $row['thumbnail_path'];
                if (file_exists($thumbnailPath)) {
                    $totalSizeBytes += filesize($thumbnailPath);
                }
            }
        }
        return number_format($totalSizeBytes / (1024 * 1024), 2) . ' MB';
    } catch (PDOException $e) {
        error_log("Error calculating disk usage: " . $e->getMessage());
        return '0 MB';
    }
}

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