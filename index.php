<?php


$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

$dbconfig = parse_ini_file(".env");
$host = $dbconfig["DB_HOST"];
$user = $dbconfig["DB_USERNAME"];

//require_once './mysqlupdates/autoload.php';
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(array('templates','views'));
$twig = new \Twig\Environment($loader);

// $david = 'THIS IS DAVID VARIABLE';
// error_log($david);

// $davidArray = array("Volvo", "BMW", "Toyota");
// error_log(print_r($davidArray,true));

$davidArray = array(
    "foo" => "bar",
    "firstname" => "David",
    "lastname" => "Terwilliger",
);
error_log("This is my access specific variable");
error_log($davidArray["foo"]);

$davidArray2 = array(
    "title" => "My Fancy Title",
    "location" => "NEwton Str boston ma",
);

error_log("This is my second array");
error_log("");
error_log("");
error_log($davidArray2["title"]);


switch ($request) {
    case '':
    case '/':
        // echo $twig ->render('base.html.twig');
       // echo $twig ->render('child.html');
        //echo $twig ->render('home.html');
        error_log("I'm insdie the root page");
        echo $twig -> render('fwwebbhome.html.twig');
        //require __DIR__ . $viewDir . 'home.php';
        break;
    
    case '/nocontent':
        echo $twig -> render('nocontent.html.twig');
        break;
    case '/events/':
        error_log("I'm inside the events page");
        echo $twig -> render('events.html.twig');
        break;
    
    case '/events2':
        error_log("I'm inside the twig events page");
        echo $twig -> render('events2.html');
        break;

    case '/testtwig':
        error_log("I'm inside the twig test");
        include 'fakedata.php';
        error_log($testImport);
        echo $twig->render('testtwig.html.twig', ['data' => $fakeData]);
        break;
    
    case '/twigfrommysql':
        error_log("I'm inside the twig mysql test");
        include 'mysqlupdates/autoload.php';
        dataLoad();
        echo $twig->render('testtwig.html.twig', ['data' => $transformedData]);
        break;

    case '/phpinfo':
        error_log("php info");
        echo phpinfo();
        break;

    default:
        http_response_code(404);
        echo $twig -> render('404.html.twig');
        break;
}
?>