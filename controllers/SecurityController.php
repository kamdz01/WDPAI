<?php
require_once 'AppController.php';
require_once __DIR__ . '/../src/repository/UserRepository.php';

class SecurityController extends AppController
{
    private $userRepository;
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // if (empty($login)) {
        //     throw new Exception('Username cannot be empty');
        // }

        // if (strlen($password) < 8) {
        //     throw new Exception('Password must be at least 8 characters long');
        // }

        // if (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        //     throw new Exception('Username can only contain letters, numbers, and underscores');
        // }

        // if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        //     throw new Exception('Password must contain at least one letter and one number');
        // }

        //TODO

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new User($login, $email, $hashedPassword);

        $this->addUser($user);

        $user = $this->getUserByEmail($email);
        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if (!($password == $user->getPassword()) && !password_verify($password, $user->getPassword())) {
        //if (!password_verify($password, $user->getPassword())) {
            //TODO
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["userId"] = $user->getId();

        return $this->render('register_ok');
    }

    public function addUser(User $newUser)
    {
        $this->userRepository->addUser($newUser);
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['login'];
        $password = $_POST['password'];

        $user = $this->getUserByEmail($email);
        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if (!($password == $user->getPassword()) && !password_verify($password, $user->getPassword())) {
        //if (!password_verify($password, $user->getPassword())) {
            //TODO
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["userId"] = $user->getId();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/panel");
    }
    private function getUserByEmail($email)
    {
        return $this->userRepository->getUserByEmail($email);
    }
    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit();
    }
}
