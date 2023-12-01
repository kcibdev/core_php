<?php

use Hp\LearningRoute\Common\Functions;

require __DIR__ . "./vendor/autoload.php";
require __DIR__ . "./src/Common/Constants.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new \Bramus\Router\Router();
$app->setNamespace("Hp\\LearningRoute\\Controllers");

$app->mount("/auth", function () use ($app) {
    $app->post("/login", "AuthController@login");
    $app->post("/register", "AuthController@register");
    $app->post("/forgot", "AuthController@forgot");
});

$app->get("/post", "PostController@post");

$app->set404(function () {
    echo Functions::abort("Page not found", 404);
});

$app->run();
