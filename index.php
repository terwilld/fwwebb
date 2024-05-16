<?php


$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

error_log("Inside Index.php", 0);
error_log("This might actually be working",0);

require_once './vendor/autoload.php';
error_log("Derp");
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);


switch ($request) {
    case '':
    case '/':
        // echo $twig ->render('base.html.twig');
       // echo $twig ->render('child.html');
        //echo $twig ->render('home.html');
        echo $twig -> render('fwwebbhome.html');
        //require __DIR__ . $viewDir . 'home.php';
        break;


    default:
        http_response_code(404);
        echo $twig -> render('404.html');
}
?>