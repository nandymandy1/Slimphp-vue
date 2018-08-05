<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Autoload Files
require ('../vendor/autoload.php');

// Database Connection
require ('../src/config/db.php');

// App Initialization
$app = new \Slim\App;

// Bring in customer Routes
require ("../src/routes/customers.php");

$app->run();