<?php
class User
{
    private $id;
    private $login;
    private $email;
    private $password;

    public function __construct(string $login, string $email, string $password)
    {
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getLogin()
    {
        return $this->login;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setLogin($login)
    {
        return $this->login = $login;
    }
    public function setEmail($email)
    {
        return $this->email = $email;
    }
    public function setPassword($password)
    {
        return $this->password = $password;
    }
    public function setId($id)
    {
        return $this->id = $id;
    }
}
