<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/album.css");
require_once "model/album.php";
require_once "model/photos.php";

$errors = [];
$action = 'edit';
$albumId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$albumData = getAlbum($pdo, $albumId);
$allPhotos = getPhotosByAlbum($pdo, $albumId);
$allAvailablePhotos = getAllPhotos($pdo);

//if (!$albumData) {
//    $errors[] = "Album not found.";
//
// need to handle the case where the album is being created
// otherwise triggers the error 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $visibility = $_POST['visibility'] ?? 'private';
    $selectedPhotos = $_POST['photos'] ?? [];

    if ($title === '') $errors[] = "Title is required.";
    if ($description === '') $errors[] = "Description is required.";

    if (empty($errors)) {
        $result = updateAlbum($pdo, $albumId, $title, $description, $visibility, $selectedPhotos);
        if ($result === true) {
            header("Location: index.php?component=albums");
            exit;
        } else {
            $errors[] = $result;
        }
    }
    // Repopulate form fields
    $albumData['title'] = $title;
    $albumData['description'] = $description;
    $albumData['visibility'] = $visibility;
    $allPhotos = getPhotosByAlbum($pdo, $albumId);
}

require "view/album.php";