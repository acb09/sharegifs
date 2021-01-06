<?php
require_once "autoload.php";

session_start();

$onlyLogged = ['index', 'feed', 'logout'];
$allowedNoLogged = ['login', 'register'];

@$isApi = isset($_GET['api']);
@$controller .= next(explode('/', $_SERVER['REQUEST_URI']));
@$controller = $controller !== '' ? $controller : 'login';
@$user = $_SESSION['user'];
@$isLogged = (bool) $user;

if ($isApi) {
    require_once 'api/' . preg_replace('/\?.*/', '', $controller) . '.php';
    exit;
}

require_once "templates/header.php";

if (array_search($controller, $allowedNoLogged) !== false)
    require_once ($isLogged) ? 'feed.php' : $controller . '.php';

else if (array_search($controller, $onlyLogged) !== false)
    if ($isLogged)
        require_once $controller . '.php';
    else
        header('Location: login');

require_once "templates/footer.php";
