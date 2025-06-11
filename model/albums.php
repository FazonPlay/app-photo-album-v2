<?php

function getAlbums(PDO $pdo, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT a.*, 
              p.thumbnail_path,
              p.file_path,
              CASE WHEN p.thumbnail_path IS NOT NULL AND p.thumbnail_path != '' 
                   THEN p.thumbnail_path 
                   ELSE p.file_path 
              END AS cover_path,
              p.is_favorite
              FROM albums a
              LEFT JOIN photos p ON a.cover_photo_id = p.photo_id
              ORDER BY a.creation_date DESC
              LIMIT :limit OFFSET :offset";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $albums = $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    $countQuery = "SELECT COUNT(*) AS total FROM albums";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['albums' => $albums, 'total' => $count['total']];
}

function deleteAlbum(PDO $pdo, int $albumId): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "DELETE FROM albums WHERE album_id = :album_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':album_id', $albumId, PDO::PARAM_INT);

    try {
        $prep->execute();
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function toggleAlbumFavorite(PDO $pdo, int $albumId, int $userId): array
{
    $query = "SELECT is_favorite FROM albums WHERE album_id = :album_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':album_id', $albumId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$album) {
            return ['success' => false, 'error' => 'Album not found'];
        }

        $newStatus = $album['is_favorite'] ? 0 : 1;

        $updateQuery = "UPDATE albums SET is_favorite = :fav WHERE album_id = :album_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindValue(':fav', $newStatus, PDO::PARAM_INT);
        $updateStmt->bindValue(':album_id', $albumId, PDO::PARAM_INT);
        $updateStmt->execute();

        return ['success' => true, 'is_favorite' => (bool)$newStatus];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

