<?php
require_once 'AppController.php';
require_once __DIR__ . '/../src/repository/UserRepo.php';

class SecurityController extends AppController
{
    private $UserRepo;
    public function __construct()
    {
        parent::__construct();
        $this->UserRepo = new UserRepo();
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new User($login, $email, $hashedPassword);

        $this->addUser($user);

        $user = $this->UserRepo->getUserByEmail($email);
        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["userId"] = $user->getId();

        return $this->render('register_ok');
    }

    public function addUser(User $newUser)
    {
        $this->UserRepo->addUser($newUser);
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['login'];
        $password = $_POST['password'];

        $user = $this->UserRepo->getUserByEmail($email);
        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["userId"] = $user->getId();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/panel");
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
        exit();
    }
}
