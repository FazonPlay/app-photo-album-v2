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
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }
    $res = $prep->fetch(PDO::FETCH_ASSOC);
    $prep->closeCursor();
    return $res;
}

function insertUser(PDO $pdo, string $username, string $email, string $password_hash, string $roles, int $is_active = 1): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO users (username, email, password_hash, roles, is_active) VALUES (:username, :email, :password_hash, :roles, :is_active)";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':username', $username);
    $prep->bindValue(':email', $email);
    $prep->bindValue(':password_hash', $password_hash);
    $prep->bindValue(':roles', $roles);
    $prep->bindValue(':is_active', $is_active, PDO::PARAM_INT);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }
    $prep->closeCursor();
    return true;
}

function updateUser(
    PDO     $pdo,
    int     $id,
    string  $username,
    ?string $password = null
): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "UPDATE users SET username = :username WHERE user_id = :id";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $id, PDO::PARAM_INT);
    $prep->bindValue(':username', $username);
    try {
        $prep->execute();
    } catch (PDOException $e) {
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }
    $prep->closeCursor();

    if (null !== $password) {
        $query = "UPDATE users SET password_hash = :password WHERE user_id = :id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':id', $id, PDO::PARAM_INT);
        $prep->bindValue(':password', $password);
        try {
            $prep->execute();
        } catch (PDOException $e) {
            return "Error: " . $e->getCode() . " - " . $e->getMessage();
        }
        $prep->closeCursor();
    }
    return true;
}