<?php
require "vendor/autoload.php";
use Illuminate\Database\Capsule\Manager;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$dbManager = new Manager();
$dbManager->addConnection([
    "driver" => "mysql",
    "host" => "localhost",
    "database" => "quizion",
    "username" => "root",
    "password" => "",
    "charset" => "utf8mb4",
    "collation" => "utf8mb4_unicode_ci",
    "prefix" => "",
]);

$dbManager->setAsGlobal();
$dbManager->bootEloquent();

$routes = require "src/routes.php";

$routes($app);

$app->run();
