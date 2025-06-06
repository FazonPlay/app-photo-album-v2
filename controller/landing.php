<?php
/**
 * @var PDO $pdo
 */

if (isset($_SESSION['auth'])) {
    registerCss("assets/css/dashboard.css");
} else {
    registerCss("assets/css/landing.css");
}
require "model/landing.php";
require "model/photo.php";

if (isset($_SESSION['auth']) && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $user = getUserData($pdo, $userId);
        if (!$user) {
            error_log("No user found with ID: $userId");
        }

        $recentAlbums = getRecentAlbums($pdo, $userId);
        $favoritePhotos = getFavoritePhotos($pdo, $userId);
        $sharedAlbums = getSharedAlbums($pdo, $userId);
        $pendingInvitations = getPendingInvitations($pdo, $userId);
    } catch (PDOException $e) {
        error_log("Database error in landing controller: " . $e->getMessage());
    }
}
require "view/landing.php";