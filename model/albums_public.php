<?php

function getPublicAlbums(PDO $pdo, int $page = 1, int $itemsPerPage = 20): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT a.*, 
              p.thumbnail_path, p.file_path,
              CASE WHEN p.thumbnail_path IS NOT NULL AND p.thumbnail_path != '' 
                   THEN p.thumbnail_path ELSE p.file_path END AS cover_path
              FROM albums a
              LEFT JOIN photos p ON a.cover_photo_id = p.photo_id
              WHERE a.visibility = 'public'
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

    $countQuery = "SELECT COUNT(*) AS total FROM albums WHERE visibility = 'public'";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['albums' => $albums, 'total' => $count['total']];
}