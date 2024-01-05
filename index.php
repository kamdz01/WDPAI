<?php
require_once 'Routing.php';
$path = trim($_SERVER['REQUEST_URI'],'/');
$path = parse_url($path, PHP_URL_PATH);

//Place to add new views
Routing::get('', 'DefaultController');
Routing::get('register', 'SecurityController');
Routing::post('login', 'SecurityController');
Routing::post('logout', 'SecurityController');
Routing::get('panel', 'DefaultController');


Routing::run($path);
?>