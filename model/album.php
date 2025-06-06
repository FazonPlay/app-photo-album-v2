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