<?php
include 'includes/database.php';

function login(PDO $pdo, string $email)
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM users WHERE email = :email";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':email', $email, PDO::PARAM_STR);
    try {
        $prep->execute();
        return $prep->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}


?>