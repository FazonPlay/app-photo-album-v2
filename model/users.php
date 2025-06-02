<?php


function getUsers(PDO $pdo, int $page = 1, int $itemsPerPage): array | string
{
    $offset = ($page - 1) * $itemsPerPage;

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM users LIMIT :limit OFFSET :offset";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);

    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }

    $users = $prep->fetchAll(PDO::FETCH_ASSOC);
    $prep->closeCursor();

    $query = "SELECT COUNT(*) AS total FROM users";
    $prep = $pdo->prepare($query);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }

    $count = $prep->fetch(PDO::FETCH_ASSOC);
    $prep->closeCursor();

    return ['users' => $users, 'total' => $count['total']];
}

function deleteUser(PDO $pdo, int $id): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "DELETE FROM users WHERE user_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Erreur: " . $e->getCode() . ' : ' . $e->getMessage();
    }
    $prep->closeCursor();

    return true;
}





