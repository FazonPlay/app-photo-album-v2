<?php

function getPhotos(PDO $pdo, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM photos ORDER BY upload_date DESC LIMIT :limit OFFSET :offset";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $photos = $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    $countQuery = "SELECT COUNT(*) AS total FROM photos";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['photos' => $photos, 'total' => $count['total']];
}

function addPhoto(PDO $pdo, string $title, string $filePath, int $userId): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO photos (title, file_path, user_id, upload_date) VALUES (:title, :file_path, :user_id, NOW())";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':title', $title);
    $prep->bindValue(':file_path', $filePath);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);

    try {
        $prep->execute();
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function deletePhoto(PDO $pdo, int $photoId): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "DELETE FROM photos WHERE photo_id = :photo_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':photo_id', $photoId, PDO::PARAM_INT);

    try {
        $prep->execute();
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getPhotosByUser(PDO $pdo, int $userId, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM photos WHERE user_id = :user_id ORDER BY upload_date DESC LIMIT :limit OFFSET :offset";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $photos = $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    $countQuery = "SELECT COUNT(*) AS total FROM photos WHERE user_id = :user_id";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['photos' => $photos, 'total' => $count['total']];
}

function getPhotoById(PDO $pdo, int $photoId) {
    $query = "SELECT * FROM photos WHERE photo_id = :photo_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetch(PDO::FETCH_ASSOC);
}

function getAllUsers(PDO $pdo): array {
    $stmt = $pdo->query("SELECT user_id, username FROM users ORDER BY username ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getPhoto(PDO $pdo, int $photoId): array|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM photos WHERE photo_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $photoId, PDO::PARAM_INT);
    try {
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        $prep->closeCursor();
        return $res;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function toggleFavorite(PDO $pdo, int $photoId, int $userId): array
{
    // Only allow toggling if the user owns the photo or has access (optional: adjust as needed)
    $photo = getPhoto($pdo, $photoId);
    if (!$photo) {
        return ['success' => false, 'error' => 'Photo not found'];
    }
    // Optionally: check $photo['user_id'] == $userId

    $newStatus = $photo['is_favorite'] ? 0 : 1;
    $query = "UPDATE photos SET is_favorite = :fav WHERE photo_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':fav', $newStatus, PDO::PARAM_INT);
    $prep->bindValue(':id', $photoId, PDO::PARAM_INT);
    try {
        $prep->execute();
        $prep->closeCursor();
        return ['success' => true, 'is_favorite' => $newStatus];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}


function updatePhoto(PDO $pdo, int $photoId, string $title, string $description, int $is_favorite = 0): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "UPDATE photos SET title = :title, description = :description, is_favorite = :is_favorite WHERE photo_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':title', $title);
    $prep->bindValue(':description', $description);
    $prep->bindValue(':is_favorite', $is_favorite, PDO::PARAM_INT);
    $prep->bindValue(':id', $photoId, PDO::PARAM_INT);
    try {
        $prep->execute();
        $prep->closeCursor();
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}