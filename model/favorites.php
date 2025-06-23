<?php
function getFavoritePhotos(PDO $pdo, int $userId, int $page = 1, int $itemsPerPage = 20): array|string {
$offset = ($page - 1) * $itemsPerPage;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT * FROM photos WHERE user_id = :user_id AND is_favorite = 1 ORDER BY upload_date DESC LIMIT :limit OFFSET :offset";
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

$countQuery = "SELECT COUNT(*) AS total FROM photos WHERE user_id = :user_id AND is_favorite = 1";
$countPrep = $pdo->prepare($countQuery);
$countPrep->bindValue(':user_id', $userId, PDO::PARAM_INT);
$countPrep->execute();
$count = $countPrep->fetch(PDO::FETCH_ASSOC);

return ['photos' => $photos, 'total' => $count['total']];
}