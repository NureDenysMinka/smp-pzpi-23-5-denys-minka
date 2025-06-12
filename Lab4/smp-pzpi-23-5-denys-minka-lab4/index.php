<?php

require_once 'db/seed.php';
require_once 'db/DbConnection.php';
require_once 'utils.php';

$path = $_SERVER['PATH_INFO'] ?? '/';

session_start();

seed();

switch ($path) {
  case '/':
    include 'pages/home.phtml';
    break;
  case '/products':
		redirectToNotFound('pages/products.phtml');
		break;
  case '/cart':
		redirectToNotFound('pages/cart.phtml');
    break;
	case '/login':
		if (!isset($_SESSION['username'])) {
			include 'pages/login.phtml';;
		} else {
			header("Location: " . "/");
		}
		break;
	case '/profile':
		redirectToNotFound('pages/profile.phtml');
		break;
  default:
    http_response_code(404);
    include 'pages/404.phtml';
		break;
}
