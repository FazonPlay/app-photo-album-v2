<?php

function getPhotos(PDO $pdo, int $page = 1, int $itemsPerPage = 20, $tagFilter = null): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($tagFilter) {
        return getPhotosByTag($pdo, $_SESSION['user_id'] ?? 0, $tagFilter, $page, $itemsPerPage);
    }

    $query = "SELECT p.*, 
              GROUP_CONCAT(t.name) as tags
              FROM photos p
              LEFT JOIN photo_tags pt ON p.photo_id = pt.photo_id
              LEFT JOIN tags t ON pt.tag_id = t.tag_id
              GROUP BY p.photo_id
              ORDER BY p.upload_date DESC 
              LIMIT :limit OFFSET :offset";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $photos = $prep->fetchAll(PDO::FETCH_ASSOC);

        // Process tags into arrays
        foreach ($photos as &$photo) {
            $photo['tags'] = $photo['tags'] ? explode(',', $photo['tags']) : [];
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    $countQuery = "SELECT COUNT(*) AS total FROM photos";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['photos' => $photos, 'total' => $count['total']];
}

function addPhoto(PDO $pdo, string $title, string $filePath, int $userId, string $description = null): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO photos (title, file_path, user_id, description, upload_date) VALUES (:title, :file_path, :user_id, :description, NOW())";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':title', $title);
    $prep->bindValue(':file_path', $filePath);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->bindValue(':description', $description);

    try {
        $prep->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function deletePhoto(PDO $pdo, int $photoId): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Begin transaction to ensure all related records are deleted
    try {
        $pdo->beginTransaction();

        // Delete tags associations first
        $tagQuery = "DELETE FROM photo_tags WHERE photo_id = :photo_id";
        $tagPrep = $pdo->prepare($tagQuery);
        $tagPrep->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
        $tagPrep->execute();

        // Delete the photo
        $query = "DELETE FROM photos WHERE photo_id = :photo_id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
        $prep->execute();

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return "Error: " . $e->getMessage();
    }
}

function getPhotosByUser(PDO $pdo, int $userId, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT p.*, 
              GROUP_CONCAT(t.name) as tags
              FROM photos p
              LEFT JOIN photo_tags pt ON p.photo_id = pt.photo_id
              LEFT JOIN tags t ON pt.tag_id = t.tag_id
              WHERE p.user_id = :user_id
              GROUP BY p.photo_id
              ORDER BY p.upload_date DESC 
              LIMIT :limit OFFSET :offset";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $photos = $prep->fetchAll(PDO::FETCH_ASSOC);

        // Process tags into arrays
        foreach ($photos as &$photo) {
            $photo['tags'] = $photo['tags'] ? explode(',', $photo['tags']) : [];
        }
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
    $query = "SELECT p.*, GROUP_CONCAT(t.name) as tag_list
              FROM photos p
              LEFT JOIN photo_tags pt ON p.photo_id = pt.photo_id
              LEFT JOIN tags t ON pt.tag_id = t.tag_id
              WHERE p.photo_id = :photo_id
              GROUP BY p.photo_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
    $prep->execute();
    $photo = $prep->fetch(PDO::FETCH_ASSOC);

    if ($photo) {
        $photo['tags'] = $photo['tag_list'] ? explode(',', $photo['tag_list']) : [];
        unset($photo['tag_list']);
    }

    return $photo;
}

function getAllUsers(PDO $pdo): array {
    $stmt = $pdo->query("SELECT user_id, username FROM users ORDER BY username ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPhoto(PDO $pdo, int $photoId): array|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT p.*, GROUP_CONCAT(t.name) as tag_list
              FROM photos p
              LEFT JOIN photo_tags pt ON p.photo_id = pt.photo_id
              LEFT JOIN tags t ON pt.tag_id = t.tag_id
              WHERE p.photo_id = :id
              GROUP BY p.photo_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $photoId, PDO::PARAM_INT);
    try {
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $res['tags'] = $res['tag_list'] ? explode(',', $res['tag_list']) : [];
            unset($res['tag_list']);
        }
        $prep->closeCursor();
        return $res;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function toggleFavorite(PDO $pdo, int $photoId, int $userId): array {
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

function updatePhoto(PDO $pdo, int $photoId, string $title, string $description, int $is_favorite = 0): bool|string {
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

function getPhotosByTag(PDO $pdo, int $userId, string $tag, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "
        SELECT p.*, GROUP_CONCAT(t2.name) as tags
        FROM photos p
        JOIN photo_tags pt ON pt.photo_id = p.photo_id
        JOIN tags t ON t.tag_id = pt.tag_id
        LEFT JOIN photo_tags pt2 ON pt2.photo_id = p.photo_id
        LEFT JOIN tags t2 ON t2.tag_id = pt2.tag_id
        LEFT JOIN albums a ON p.album_id = a.album_id
        WHERE t.name = :tag
        AND (
            p.user_id = :user_id
            OR (a.album_id IS NOT NULL AND a.visibility = 'public')
            OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id)
        )
        GROUP BY p.photo_id
        ORDER BY p.upload_date DESC
        LIMIT :limit OFFSET :offset
    ";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':tag', $tag);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $photos = $prep->fetchAll(PDO::FETCH_ASSOC);

        // Process tags into arrays
        foreach ($photos as &$photo) {
            $photo['tags'] = $photo['tags'] ? explode(',', $photo['tags']) : [];
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    // Count
    $countQuery = "
        SELECT COUNT(DISTINCT p.photo_id) AS total
        FROM photos p
        JOIN photo_tags pt ON pt.photo_id = p.photo_id
        JOIN tags t ON t.tag_id = pt.tag_id
        LEFT JOIN albums a ON p.album_id = a.album_id
        WHERE t.name = :tag
        AND (
            p.user_id = :user_id
            OR (a.album_id IS NOT NULL AND a.visibility = 'public')
            OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id)
        )
    ";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->bindValue(':tag', $tag);
    $countPrep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['photos' => $photos, 'total' => $count['total']];
}

function addTagToPhoto(PDO $pdo, int $photoId, string $tagName): array {
    try {
        $pdo->beginTransaction();

        // Check if tag exists
        $checkQuery = "SELECT tag_id FROM tags WHERE name = :name";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindValue(':name', $tagName);
        $checkStmt->execute();
        $tagId = $checkStmt->fetchColumn();

        // Create tag if it doesn't exist
        if (!$tagId) {
            $createQuery = "INSERT INTO tags (name, created_at) VALUES (:name, NOW())";
            $createStmt = $pdo->prepare($createQuery);
            $createStmt->bindValue(':name', $tagName);
            $createStmt->execute();
            $tagId = $pdo->lastInsertId();
        }

        // Associate tag with photo if not already
        $assocQuery = "INSERT IGNORE INTO photo_tags (photo_id, tag_id) VALUES (:photo_id, :tag_id)";
        $assocStmt = $pdo->prepare($assocQuery);
        $assocStmt->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
        $assocStmt->bindValue(':tag_id', $tagId, PDO::PARAM_INT);
        $assocStmt->execute();

        $pdo->commit();
        return ['success' => true, 'tag_id' => $tagId, 'tag' => $tagName];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function removeTagFromPhoto(PDO $pdo, int $photoId, string $tagName): array {
    try {
        // Get tag ID
        $tagQuery = "SELECT tag_id FROM tags WHERE name = :name";
        $tagStmt = $pdo->prepare($tagQuery);
        $tagStmt->bindValue(':name', $tagName);
        $tagStmt->execute();
        $tagId = $tagStmt->fetchColumn();

        if (!$tagId) {
            return ['success' => false, 'error' => 'Tag not found'];
        }

        // Delete association
        $deleteQuery = "DELETE FROM photo_tags WHERE photo_id = :photo_id AND tag_id = :tag_id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
        $deleteStmt->bindValue(':tag_id', $tagId, PDO::PARAM_INT);
        $deleteStmt->execute();

        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getAvailableTags(PDO $pdo): array {
    try {
        $query = "SELECT name FROM tags ORDER BY name";
        $stmt = $pdo->query($query);
        $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return ['success' => true, 'tags' => $tags];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}