<?php

function getAlbums(PDO $pdo, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT a.*, p.thumbnail_path AS cover_path
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

