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
                echo $twig ->render('child.html');
        
        //require __DIR__ . $viewDir . 'home.php';
        break;

    case '/test':
        echo $twig -> render('child.html');
        break;

    case '/newhome':
        echo $twig -> render('home2.html');
        break;
        
    case '/views/users':
        require __DIR__ . $viewDir . 'users.php';
        break;

    case '/contact':
        require __DIR__ . $viewDir . 'contact.php';
        break;

    case '/twig':
        require __DIR__ . $viewDir . 'twig.php';
        break;


    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}
?>