<?php

function addAlbum(PDO $pdo, string $title, string $description, $coverPhotoId = null) {
    $userId = $_SESSION['user_id'] ?? 0;
    $query = "INSERT INTO albums (user_id, title, description, cover_photo_id, creation_date) VALUES (:user_id, :title, :description, :cover_photo_id, NOW())";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->bindValue(':title', $title);
    $prep->bindValue(':description', $description);
    $prep->bindValue(':cover_photo_id', $coverPhotoId, PDO::PARAM_INT);
    $prep->execute();
    return $pdo->lastInsertId();
}

function addPhotoToAlbum(PDO $pdo, int $albumId, int $photoId) {
    $query = "UPDATE photos SET album_id = :album_id WHERE photo_id = :photo_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':album_id', $albumId, PDO::PARAM_INT);
    $prep->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
    $prep->execute();
}



function getAlbum(PDO $pdo, int $albumId): array|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM albums WHERE album_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $albumId, PDO::PARAM_INT);
    try {
        $prep->execute();
        $res = $prep->fetch(PDO::FETCH_ASSOC);
        $prep->closeCursor();
        return $res;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function updateAlbum(PDO $pdo, int $albumId, string $title, string $description, string $visibility, array $photoIds): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $pdo->beginTransaction();

        $query = "UPDATE albums SET title = :title, description = :description, visibility = :visibility WHERE album_id = :id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':title', $title);
        $prep->bindValue(':description', $description);
        $prep->bindValue(':visibility', $visibility);
        $prep->bindValue(':id', $albumId, PDO::PARAM_INT);
        $prep->execute();

        $pdo->prepare("UPDATE photos SET album_id = NULL WHERE album_id = :album_id")
            ->execute([':album_id' => $albumId]);

        if (!empty($photoIds)) {
            $stmt = $pdo->prepare("UPDATE photos SET album_id = :album_id WHERE photo_id = :photo_id");
            foreach ($photoIds as $photoId) {
                $stmt->bindValue(':album_id', $albumId, PDO::PARAM_INT);
                $stmt->bindValue(':photo_id', $photoId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        if (!empty($photoIds)) {
            $coverPhotoId = $photoIds[0]; // Use first photo as cover if not specified
            $pdo->prepare("UPDATE albums SET cover_photo_id = :cover_id WHERE album_id = :album_id")
                ->execute([':cover_id' => $coverPhotoId, ':album_id' => $albumId]);
        }

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return "Error: " . $e->getMessage();
    }
}

function getPhotosByAlbum(PDO $pdo, int $albumId): array
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM photos WHERE album_id = :album_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':album_id', $albumId, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPhotos(PDO $pdo): array
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM photos ORDER BY upload_date DESC";
    $prep = $pdo->prepare($query);
    $prep->execute();
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}