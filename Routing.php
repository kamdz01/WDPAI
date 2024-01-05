<?php

require_once 'controllers/DefaultController.php';
require_once 'controllers/SecurityController.php';
require_once 'controllers/ErrorController.php';

class Routing{
  public static $routes;

  public static function get($url, $view) {
    self::$routes[$url] = $view;
  }

  public static function post($url, $view)
  {
    self::$routes[$url] = $view;
  }

  public static function run($url)
  {
    $action = explode("/", $url)[0];
    $errorController = new ErrorController();

    if (!array_key_exists($action, self::$routes)) {
      $errorController->handle('wrong_url');
    }

    $controller = self::$routes[$action];
    $object = new $controller;
    $action = $action ?: 'index';

    $object->$action();
  }
}