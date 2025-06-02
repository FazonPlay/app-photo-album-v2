<?php

function getUser(PDO $pdo, int $id): array|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM users WHERE user_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return " erreur : " . $e->getCode() . ' :</b> ' . $e->getMessage();
    }

    $res = $prep->fetch();
    $prep->closeCursor();

    return $res;
}

function insertUser(PDO $pdo, string $username, string $password): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':password', $password);
    $prep->bindValue(':username', $username);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Erreur: " . $e->getCode() . ' : ' . $e->getMessage();
    }
    $prep->closeCursor();

    return true;
}


function updateUser(
    PDO     $pdo,
    int     $id,
    string  $username,
    ?string $password = null,
): bool|string
{

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "UPDATE users SET username = :username WHERE id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $id, PDO::PARAM_INT);
    $prep->bindValue(':username', $username);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return " erreur : " . $e->getCode() . ' :</b> ' . $e->getMessage();
    }
    $prep->closeCursor();

    if (null !== $password) {
        $query = "UPDATE users SET password = :password WHERE id = :id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':id', $id, PDO::PARAM_INT);
        $prep->bindValue(':password', $password);
        try {
            $prep->execute();
        } catch (PDOException $e) {
            return " erreur : " . $e->getCode() . ' :</b> ' . $e->getMessage();
        }
        $prep->closeCursor();
    }


    return true;
}