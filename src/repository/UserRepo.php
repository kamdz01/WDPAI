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

    public function getUserRoleByUserId($userId) {
        $sql = "SELECT r.role_id
                FROM roles r
                JOIN users u ON r.role_id = u.role_id
                WHERE u.user_id = :userId";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['role_id'] : null;
    }
    
    public function changeUserRole($userId, $newRoleId) {
        $sql = "UPDATE users SET role_id = :newRoleId WHERE user_id = :userId";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':newRoleId', $newRoleId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        return true;
    }

    public function searchUserByUsernameOrEmail($input) {
        $sql = "SELECT u.user_id, u.login, u.email, r.role_name 
        FROM users u
        JOIN roles r ON u.role_id = r.role_id
        WHERE u.login = :input OR u.email = :input";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':input', $input, PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function getAllUserRoles() {
        $sql = "SELECT role_id, role_name FROM roles";
        $stmt = $this->database->connect()->prepare($sql);

        $stmt->execute();

        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $roles;
    }
    
    function getUserIdByUsername($username) {

        $sql = "SELECT user_id FROM users WHERE login = :username";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['user_id'] : null;
    }

    function deleteUserById($userId) {
        $sql = "DELETE FROM users WHERE user_id = :userId";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    
}
