<?php
/**
 * @var PDO $pdo
 */

function getUserData($pdo, $userId) {
    $query = "SELECT u.*, up.profile_picture
              FROM users u
              LEFT JOIN user_profiles up ON u.user_id = up.user_id
              WHERE u.user_id = :userId";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', (int)$userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

function getRecentAlbums($pdo, $userId, $limit = 4) {
    $limit = (int)$limit; // Avoid binding LIMIT directly
    $query = "SELECT a.*,
              (SELECT COUNT(*) FROM photos WHERE album_id = a.album_id) AS photoCount,
              (SELECT file_path FROM photos WHERE photo_id = a.cover_photo_id LIMIT 1) AS coverPhotoUrl
              FROM albums a
              WHERE a.user_id = :userId
              ORDER BY a.creation_date DESC
              LIMIT $limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', (int)$userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getFavoritePhotos($pdo, $userId, $limit = 6) {
    $limit = (int)$limit;
    $query = "SELECT p.* 
              FROM photos p
              WHERE p.user_id = :userId AND p.is_favorite = 1
              ORDER BY p.upload_date DESC
              LIMIT $limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', (int)$userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getSharedAlbums($pdo, $userId, $limit = 4) {
    $limit = (int)$limit;
    $query = "SELECT a.*, u.username AS ownerName,
              (SELECT file_path FROM photos WHERE photo_id = a.cover_photo_id LIMIT 1) AS coverPhotoUrl
              FROM albums a
              JOIN album_access aa ON a.album_id = aa.album_id
              JOIN users u ON a.user_id = u.user_id
              WHERE aa.user_id = :userId AND a.user_id != :userId
              ORDER BY aa.granted_at DESC
              LIMIT $limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', (int)$userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getPendingInvitations($pdo, $userId) {
    $query = "SELECT ai.*, a.title AS albumTitle, u.username AS senderName
              FROM album_invitations ai
              JOIN albums a ON ai.album_id = a.album_id
              JOIN users u ON ai.sender_id = u.user_id
              JOIN users ur ON ur.user_id = :userId
              WHERE ai.recipient_email = ur.email
              AND ai.is_accepted IS NULL
              ORDER BY ai.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':userId', (int)$userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
