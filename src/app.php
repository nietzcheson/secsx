<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Application\SecurityTrait;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Application();


date_default_timezone_set('America/Cancun');

$app->register(new RoutingServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
  'host' => 'mail.sinergiafc.com',
  'port' => '25',
  'username' => '',
  'password' => '',
  'encryption' => null,
  'auth_mode' => null
);

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
  'locale' => 'en',
));

$app['security.firewalls'] = array(
  'login' => array(
    'pattern' => '^/login$'
  ),
  'secured' => array(
    'pattern' => '^/',
    'form' => array('login_path' => '/login','check_path' => '/admin/login_check'),
    'logout' => array('logout_path' => '/admin/logout'),
    'users' => array('admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==')),
    ),
    'unsecured' => array(
      'anonymous' => true,
    ),
);

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'dbs.options' => array (
  'mysql_read' => array(
    'driver'    => 'pdo_mysql',
    'host'      => 'localhost',
    'dbname'    => 'sisfc',
    'user'      => 'sisfc',
    'password'  => 'sisfc',
    'charset'   => 'utf8',
  ),
),
));


$app["clientes"] = function() use($app){

  $clientes = $app["db"]->fetchAll("SELECT * FROM marcas");

  return $clientes;
};


$app["prospectos"] = function($id_cliente) use ($app){
  return $id_cliente;
};


$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return $app['request_stack']->getMasterRequest()->getBasepath().'/'.$asset;
    }));

    return $twig;
});

return $app;
