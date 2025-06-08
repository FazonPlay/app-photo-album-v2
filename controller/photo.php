<?php
/**
 * @var PDO $pdo
 */
require_once "model/photos.php";
registerCss("assets/css/album.css");
registerCss("assets/css/dashboard.css");

$errors = [];
$action = 'edit';
$photoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$photoData = getPhoto($pdo, $photoId);

if (!$photoData) {
    $errors[] = "Photo not found.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_favorite = isset($_POST['is_favorite']) ? 1 : 0;

    if ($title === '') $errors[] = "Title is required.";

    if (empty($errors)) {
        $result = updatePhoto($pdo, $photoId, $title, $description, $is_favorite);
        if ($result === true) {
            header("Location: index.php?component=photos");
            exit;
        } else {
            $errors[] = $result;
        }
    }
    // Repopulate form fields
    $photoData['title'] = $title;
    $photoData['description'] = $description;
    $photoData['is_favorite'] = $is_favorite;
}

require "view/photo.php";