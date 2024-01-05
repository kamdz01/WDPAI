<?php
require_once 'Repository.php';
require_once __DIR__ . '/../../models/User.php';

class UserRepo extends Repository
{
    public function getAllUsers(): array
    {
        $result = [];
        $stmt = $this->database->connect()->prepare(
            'SELECT * FROM users;'
        );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            $userObj = new User(
                $user['login'],
                $user['email'],
                $user['password'],
            );
            $userObj->setId($user['id']);
            $result[] = $userObj;
        }

        return $result;
    }

    public function getUserByEmail(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare(
            'SELECT * FROM users WHERE email = :email'
        );
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $userObj = new User(
                $user['login'],
                $user['email'],
                $user['password']
            );
            $userObj->setId($user['user_id']);
            return $userObj;
        }

        return null;
    }
    public function addUser(User $user)
    {
        $stmt = $this->database->connect()->prepare(
            'INSERT INTO users (login, email, password) VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $user->getLogin(),
            $user->getEmail(),
            $user->getPassword(),
        ]);
    }
}
