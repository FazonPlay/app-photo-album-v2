<?php
function declineAlbumInvitation(PDO $pdo, string $token)
{
    $stmt = $pdo->prepare("UPDATE album_invitations SET is_accepted = 'refused' WHERE token = :token AND is_accepted IS NULL AND expires_at > NOW()");
    $stmt->execute([':token' => $token]);
    return $stmt->rowCount() > 0;
}

function getPendingInvitations(PDO $pdo, int $userId)
{
    $stmt = $pdo->prepare("
        SELECT ai.*, a.title as album_title, u.username as sender_name 
        FROM album_invitations ai
        JOIN albums a ON ai.album_id = a.album_id
        JOIN users u ON ai.sender_id = u.user_id
        JOIN users ur ON ur.user_id = :userId
        WHERE ai.recipient_email = ur.email
        AND ai.is_accepted IS NULL 
        AND ai.expires_at > NOW()
        ORDER BY ai.created_at DESC
    ");
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function acceptAlbumInvitation(PDO $pdo, string $token, int $userId): bool {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM album_invitations 
                              WHERE token = :token 
                              AND is_accepted IS NULL 
                              AND expires_at > NOW()");
        $stmt->execute([':token' => $token]);
        $invitation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invitation) {
            $pdo->rollBack();
            return false;
        }

        $accessStmt = $pdo->prepare("INSERT INTO album_access 
                                    (album_id, user_id, permission_level, granted_by) 
                                    VALUES (:album_id, :user_id, :permission, :granted_by)
                                    ON DUPLICATE KEY UPDATE permission_level = :permission");
        $accessStmt->execute([
            ':album_id' => $invitation['album_id'],
            ':user_id' => $userId,
            ':permission' => $invitation['permission_level'],
            ':granted_by' => $invitation['sender_id']
        ]);

        $updateStmt = $pdo->prepare("UPDATE album_invitations 
                                    SET is_accepted = 'accepted' 
                                    WHERE invitation_id = :id");
        $updateStmt->execute([':id' => $invitation['invitation_id']]);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error accepting invitation: " . $e->getMessage());
        return false;
    }
}