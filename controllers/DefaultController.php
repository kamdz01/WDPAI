<?php

require_once 'AppController.php';
require_once 'SecurityController.php';
class DefaultController extends AppController
{
    public function register()
    {
        $this->render('register');
    }
    public function login()
    {
        $this->render('login');
    }
    public function logout()
    {
        $this->render('logout');
    }
    public function panel()
    {
        $this->render('panel');
    }
    public function admin()
    {
        $this->render('admin');
    }
    public function index()
    {
        $this->render('main');
    }
}
