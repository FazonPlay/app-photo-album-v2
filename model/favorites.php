<?php
//
//function getFavoritePhotos(PDO $pdo, int $userId): array|string {
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $query = "SELECT * FROM photos WHERE user_id = :user_id AND is_favorite = 1";
//    $prep = $pdo->prepare($query);
//    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
//    try {
//        $prep->execute();
//        return $prep->fetchAll(PDO::FETCH_ASSOC);
//    } catch (PDOException $e) {
//        return "Error: " . $e->getMessage();
//    }
//}
//
//function setPhotoFavorite(PDO $pdo, int $photoId, int $userId, bool $favorite): bool|string {
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $query = "UPDATE photos SET is_favorite = :fav WHERE photo_id = :pid AND user_id = :uid";
//    $prep = $pdo->prepare($query);
//    $prep->bindValue(':fav', $favorite ? 1 : 0, PDO::PARAM_INT);
//    $prep->bindValue(':pid', $photoId, PDO::PARAM_INT);
//    $prep->bindValue(':uid', $userId, PDO::PARAM_INT);
//    try {
//        $prep->execute();
//        return true;
//    } catch (PDOException $e) {
//        return "Error: " . $e->getMessage();
//    }
//}