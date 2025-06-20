<?php

function getSharedAlbums(PDO $pdo, int $userId): array
{
    $query = "SELECT a.*, p.thumbnail_path, p.file_path,
                CASE WHEN p.thumbnail_path IS NOT NULL AND p.thumbnail_path != '' THEN p.thumbnail_path ELSE p.file_path END AS cover_path,
                aa.permission_level
              FROM albums a
              INNER JOIN album_access aa ON a.album_id = aa.album_id
              LEFT JOIN photos p ON a.cover_photo_id = p.photo_id
              WHERE aa.user_id = :user_id
              ORDER BY a.creation_date DESC";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}