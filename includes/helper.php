<?php

function cleanString(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES));
}


function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isAlbumOwner($album, $userId)
{
    return $album && $album['user_id'] == $userId;
}

function isPhotoOwner($photo, $userId)
{
    return $photo && $photo['user_id'] == $userId;
}

function canViewAlbum($album, $userId)
{
    if (!$album) return false;
    if (isAdmin() || isAlbumOwner($album, $userId)) return true;
    if ($album['visibility'] === 'public') return true;
    if ($album['visibility'] === 'restricted') {
        // Check album_access table
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM album_access WHERE album_id = :album_id AND user_id = :user_id");
        $stmt->execute([':album_id' => $album['album_id'], ':user_id' => $userId]);
        return $stmt->fetch() !== false;
    }
    return false;
}

function getAlbumPermission($albumId, $userId)
{
    global $pdo;
    if (isAdmin()) return 'contribute';
    $stmt = $pdo->prepare("SELECT permission_level FROM album_access WHERE album_id = :album_id AND user_id = :user_id");
    $stmt->execute([':album_id' => $albumId, ':user_id' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['permission_level'] : null;
}

function canEditAlbum($album, $userId)
{
    if (isAdmin() || isAlbumOwner($album, $userId)) return true;
    if ($album['visibility'] === 'restricted') {
        $perm = getAlbumPermission($album['album_id'], $userId);
        return $perm === 'contribute';
    }
    return false;
}

function canCommentAlbum($album, $userId)
{
    if (isAdmin() || isAlbumOwner($album, $userId)) return true;
    if ($album['visibility'] === 'restricted') {
        $perm = getAlbumPermission($album['album_id'], $userId);
        return in_array($perm, ['comment', 'contribute']);
    }
    return $album['visibility'] === 'public';
}

function canViewPhoto($photo, $userId)
{
    if (!$photo) return false;
    if (isAdmin() || isPhotoOwner($photo, $userId)) return true;
    // Get album and check album permissions
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = :album_id");
    $stmt->execute([':album_id' => $photo['album_id']]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);
    return canViewAlbum($album, $userId);
}

