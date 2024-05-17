<?php

require_once './vendor/autoload.php';

$loader = new \Twig\Loader\ArrayLoader([
    'index' => 'child.html',
]);

$twig = new \Twig\Environment($loader);

echo $twig->render('../templates/child.html', ['name' => 'Fabien']);
// echo $twig->render('index', ['name' => 'Fabien']);

?>