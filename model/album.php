<?php

function addAlbum(PDO $pdo, string $title, string $description, $coverPhotoId = null) {
    $query = "INSERT INTO albums (title, description, cover_photo_id, creation_date) VALUES (:title, :description, :cover_photo_id, NOW())";
    $prep = $pdo->prepare($query);
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
    $query = "UPDATE albums SET title = :title, description = :description, visibility = :visibility WHERE album_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':title', $title);
    $prep->bindValue(':description', $description);
    $prep->bindValue(':visibility', $visibility);
    $prep->bindValue(':id', $albumId, PDO::PARAM_INT);
    try {
        $prep->execute();
        // Remove all current photos from album
        $pdo->prepare("UPDATE photos SET album_id = NULL WHERE album_id = :album_id")->execute([':album_id' => $albumId]);
        // Add selected photos to album
        if (!empty($photoIds)) {
            $in = implode(',', array_fill(0, count($photoIds), '?'));
            $stmt = $pdo->prepare("UPDATE photos SET album_id = ? WHERE photo_id IN ($in)");
            foreach ($photoIds as $photoId) {
                $stmt->execute([$albumId, $photoId]);
            }
        }
        return true;
    } catch (PDOException $e) {
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