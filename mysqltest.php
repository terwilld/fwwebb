<?php

    $env = parse_ini_file('.env');

    $host =  $env["DB_HOST"];
    $user =  $env["DB_USERNAME"];
    $pass = $env["DB_PASSWORD"];
    
    
    
    
    $db = $env["DB_DATABASE"];


    echo 'My username is ' .$env["DB_USERNAME"] . '!';
    
    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_errno)
        {
            printf("<br><br><br>");
            printf("Error connect: %s\n", $mysqli->connect_error);
            exit();
        }

    
    $table = 'events';
    $sql = "CREATE TABLE IF NOT EXISTS $table (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, firstname VARCHAR(30) NOT NULL,lastname VARCHAR(30) NOT NULL)";
    $sql = "Select * from pet;";
    $result = mysqli_query($mysqli,$sql);
    while ($row = mysqli_fetch_array($result)) {
    print_r($row);
    echo('<br>');
    }


?>