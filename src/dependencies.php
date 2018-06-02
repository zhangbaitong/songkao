<?php
// DIC configuration
use models\User;
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
$container['\HomeController'] = function($c) {
    return new HomeController($c);
};
$container['\TokenUtil'] = function($c) {
    return new TokenUtil($c);
};
$container['db'] = function ($c){
    $settings = $c->get('settings')['db'];
    $dsn = 'mysql:host=' .$settings['host'].
        ';dbname='    .$settings['database'] .
        ';port='      .$settings['port'] .
        ';connect_timeout=15';
    // getting DB user from config
    $user = $settings['username'] ;
    // getting DB password from config
    $password = $settings['password'] ;
    $c ->logger ->error("dsn >>>".$dsn);

    try{
        $dbh =  new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

    }catch (PDOException $e){
        $c ->logger ->error($e ->getMessage());
    }
    return $dbh;
};
$container['user'] = function ($c){
    return new User($c -> get('db') ,$c);
};
return $container;