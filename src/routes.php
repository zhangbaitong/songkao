<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
//$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});
$app->get('/', \HomeController::class);
$app ->map(['GET','POST'] ,'/auth','\HomeController:auth');
$app ->map(['GET','POST'] ,'/joinUser','\HomeController:joinUser');
$app ->get('/show','\HomeController:show');
$app ->get('/join',\HomeController::class . ':join');
