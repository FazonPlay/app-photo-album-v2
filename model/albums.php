<?php

function getAlbums(PDO $pdo, int $page = 1, int $itemsPerPage = 20, ?int $userId = null): array|string {
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure limit and offset are valid integers to prevent SQL injection
    $limit = (int)$itemsPerPage;
    $offset = (int)$offset;

    $params = [];
    $whereClause = "";

    if ($userId !== null) {
        // Admin or filtered by user ID
        $whereClause = "WHERE a.user_id = :user_id";
        $params[':user_id'] = $userId;
    } else if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
        // Regular user: show their own, public, or shared albums
        $currentUserId = $_SESSION['user_id'];
        $whereClause = "WHERE (a.user_id = :user_id OR a.visibility = 'public' 
            OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id))";
        $params[':user_id'] = $currentUserId;
    }

    $query = "SELECT a.*, 
              p.thumbnail_path, p.file_path,
              CASE WHEN p.thumbnail_path IS NOT NULL AND p.thumbnail_path != '' 
                   THEN p.thumbnail_path ELSE p.file_path END AS cover_path,
              p.is_favorite
              FROM albums a
              LEFT JOIN photos p ON a.cover_photo_id = p.photo_id
              $whereClause
              ORDER BY a.creation_date DESC
              LIMIT $limit OFFSET $offset";

    $prep = $pdo->prepare($query);

    foreach ($params as $key => $value) {
        $type = (strpos($key, 'user_id') !== false) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $prep->bindValue($key, $value, $type);
    }

    try {
        $prep->execute();
        $albums = $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    $countQuery = "SELECT COUNT(*) AS total FROM albums a $whereClause";
    $countPrep = $pdo->prepare($countQuery);

    foreach ($params as $key => $value) {
        $type = (strpos($key, 'user_id') !== false) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $countPrep->bindValue($key, $value, $type);
    }

    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['albums' => $albums, 'total' => $count['total']];
}


function deleteAlbum(PDO $pdo, int $albumId): bool|string {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "DELETE FROM albums WHERE album_id = :album_id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':album_id', $albumId, PDO::PARAM_INT);

    try {
        $prep->execute();
        return true;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function getAlbumsByTag(PDO $pdo, int $userId, string $tag, int $page = 1, int $itemsPerPage = 20): array|string
{
    $offset = ($page - 1) * $itemsPerPage;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Only albums the user owns or has access to
    $query = "
        SELECT DISTINCT a.*, 
            p.thumbnail_path, p.file_path,
            CASE WHEN p.thumbnail_path IS NOT NULL AND p.thumbnail_path != '' THEN p.thumbnail_path ELSE p.file_path END AS cover_path
        FROM albums a
        JOIN photos ph ON ph.album_id = a.album_id
        JOIN photo_tags pt ON pt.photo_id = ph.photo_id
        JOIN tags t ON t.tag_id = pt.tag_id
        LEFT JOIN photos p ON a.cover_photo_id = p.photo_id
        WHERE t.name = :tag
        AND (
            a.user_id = :user_id
            OR a.visibility = 'public'
            OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id)
        )
        ORDER BY a.creation_date DESC
        LIMIT :limit OFFSET :offset
    ";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':tag', $tag);
    $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
        $albums = $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }

    // Count
    $countQuery = "
        SELECT COUNT(DISTINCT a.album_id) AS total
        FROM albums a
        JOIN photos ph ON ph.album_id = a.album_id
        JOIN photo_tags pt ON pt.photo_id = ph.photo_id
        JOIN tags t ON t.tag_id = pt.tag_id
        WHERE t.name = :tag
        AND (
            a.user_id = :user_id
            OR a.visibility = 'public'
            OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id)
        )
    ";
    $countPrep = $pdo->prepare($countQuery);
    $countPrep->bindValue(':tag', $tag);
    $countPrep->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $countPrep->execute();
    $count = $countPrep->fetch(PDO::FETCH_ASSOC);

    return ['albums' => $albums, 'total' => $count['total']];
}

// Album invitation logic
function inviteUserToAlbum(PDO $pdo, int $albumId, int $senderId, string $recipientEmail, string $permission, string $message, string $expiresAt)
{
    $token = bin2hex(random_bytes(16));
    $query = "INSERT INTO album_invitations (album_id, sender_id, recipient_email, token, permission_level, message, expires_at) 
              VALUES (:album_id, :sender_id, :recipient_email, :token, :permission_level, :message, :expires_at)";
    $prep = $pdo->prepare($query);
    $prep->execute([
        ':album_id' => $albumId,
        ':sender_id' => $senderId,
        ':recipient_email' => $recipientEmail,
        ':token' => $token,
        ':permission_level' => $permission,
        ':message' => $message,
        ':expires_at' => $expiresAt
    ]);
    return $token;
}

function acceptAlbumInvitation(PDO $pdo, string $token, int $userId)
{
    // Find invitation
    $stmt = $pdo->prepare("SELECT * FROM album_invitations WHERE token = :token AND is_accepted IS NULL AND expires_at > NOW()");
    $stmt->execute([':token' => $token]);
    $inv = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$inv) return false;

    // Grant access
    $pdo->prepare("INSERT IGNORE INTO album_access (album_id, user_id, permission_level, granted_by) VALUES (:album_id, :user_id, :perm, :granted_by)")
        ->execute([
            ':album_id' => $inv['album_id'],
            ':user_id' => $userId,
            ':perm' => $inv['permission_level'],
            ':granted_by' => $inv['sender_id']
        ]);
    $pdo->prepare("UPDATE album_invitations SET is_accepted = 'accepted' WHERE invitation_id = :id")
        ->execute([':id' => $inv['invitation_id']]);
    return true;
}





