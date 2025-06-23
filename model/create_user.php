<?php
function create_user(
    PDO    $pdo,
    string $username,
    string $email,
    string $password,
): ?int
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO users (username, email, password_hash, roles) 
              VALUES (:username, :email, :password_hash, :roles)";
    $prep = $pdo->prepare($query);
    try {
        $prep->bindValue(':username', $username, PDO::PARAM_STR);
        $prep->bindValue(':email', $email, PDO::PARAM_STR);
        $prep->bindValue(':password_hash', $password, PDO::PARAM_STR);
        $prep->bindValue(':roles', 'user', PDO::PARAM_STR);
        $prep->execute();

        return (int)$pdo->lastInsertId();

    } catch (PDOException $e) {
        error_log("Create user error: " . $e->getMessage());
        return null;
    }
}
