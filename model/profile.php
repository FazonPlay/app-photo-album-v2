<?php

function getUserProfile(PDO $pdo, int $user_id): array|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT u.user_id, u.username, u.email, u.roles 
              FROM users u 
              WHERE u.user_id = :user_id";

    $prep = $pdo->prepare($query);
    $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    try {
        $prep->execute();
        $userData = $prep->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return "User not found";
        }

        $query = "SELECT p.first_name, p.last_name, p.bio, p.profile_picture 
                  FROM user_profiles p 
                  WHERE p.user_id = :user_id";

        $prep = $pdo->prepare($query);
        $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $prep->execute();
        $profileData = $prep->fetch(PDO::FETCH_ASSOC);

        return array_merge($userData, $profileData ?: []);

    } catch (PDOException $e) {
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }
}

function updateUserProfile(PDO $pdo, int $user_id, array $data): bool|string
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $pdo->beginTransaction();

        $query = "UPDATE users SET username = :username, email = :email WHERE user_id = :user_id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':username', $data['username']);
        $prep->bindValue(':email', $data['email']);
        $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $prep->execute();

        if (!empty($data['password'])) {
            $query = "UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id";
            $prep = $pdo->prepare($query);
            $prep->bindValue(':password_hash', password_hash($data['password'], PASSWORD_BCRYPT));
            $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $prep->execute();
        }

        $query = "SELECT profile_id FROM user_profiles WHERE user_id = :user_id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $prep->execute();
        $profileExists = $prep->fetch(PDO::FETCH_ASSOC);

        if ($profileExists) {
            $query = "UPDATE user_profiles 
                      SET first_name = :first_name, 
                          last_name = :last_name, 
                          bio = :bio";

            if (!empty($data['profile_picture'])) {
                $query .= ", profile_picture = :profile_picture";
            }

            $query .= " WHERE user_id = :user_id";

            $prep = $pdo->prepare($query);
            $prep->bindValue(':first_name', $data['first_name'] ?? null);
            $prep->bindValue(':last_name', $data['last_name'] ?? null);
            $prep->bindValue(':bio', $data['bio'] ?? null);

            if (!empty($data['profile_picture'])) {
                $prep->bindValue(':profile_picture', $data['profile_picture']);
            }

            $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $prep->execute();

        } else {
            $query = "INSERT INTO user_profiles (user_id, first_name, last_name, bio, profile_picture)
                      VALUES (:user_id, :first_name, :last_name, :bio, :profile_picture)";

            $prep = $pdo->prepare($query);
            $prep->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $prep->bindValue(':first_name', $data['first_name'] ?? null);
            $prep->bindValue(':last_name', $data['last_name'] ?? null);
            $prep->bindValue(':bio', $data['bio'] ?? null);
            $prep->bindValue(':profile_picture', $data['profile_picture'] ?? null);
            $prep->execute();
        }

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        $pdo->rollBack();
        return "Error: " . $e->getCode() . " - " . $e->getMessage();
    }
}