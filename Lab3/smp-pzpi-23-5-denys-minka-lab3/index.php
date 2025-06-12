<?php

require_once 'db/seed.php';
require_once 'db/DbConnection.php';

$path = $_SERVER['PATH_INFO'] ?? '/';

session_start();

seed();

switch ($path) {
  case '/':
    include 'pages/home.phtml';
    break;
  case '/products':
    include 'pages/products.phtml';
    break;
  case '/cart':
    include 'pages/cart.phtml';;
    break;
  default:
    http_response_code(404);
    include 'pages/404.phtml';
		break;
}
