<?php
namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$loader = new FilesystemLoader(__DIR__ . '/../src/templates/');
$view = new Environment($loader);

$logger = new Logger('action');
$streamHandler = new StreamHandler(__DIR__ . '/../src/log/action.log', Logger::INFO);
$logger->pushHandler($streamHandler);

$messenger = new Messenger($view, $logger);

if (isset($_GET['login']) && isset($_GET['password']) && isset($_GET['message']))  {
    $lg = (string)$_GET['login'];
    $ps = (string)$_GET['password'];
    $ms = (string)$_GET['message'];
    $messenger->addMessage($lg, $ps, $ms);
    header('Location: /twig/public');
}
