<?php



    $env = parse_ini_file('.env');

    printf("Test0\n");

    printf("TEST1\n");
    $host =  $env["DB_HOST"];
    $user =  $env["DB_USERNAME"];
    $pass = $env["DB_PASSWORD"];
    $db = $env["DB_DATABASE"];
    printf("Fuck you\n");
    printf($host);
    echo 'My username is ' .$env["DB_USERNAME"] . '!';
    printf("\n");
    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_errno)
        {
            printf("<br><br><br>");
            printf("Error connect: %s\n", $mysqli->connect_error);
            exit();
        }
    echo('<br>');
    echo 'davidtest<br>';
    
    $sql = "Select * from pet;";
    $result = mysqli_query($mysqli,$sql);
    while ($row = mysqli_fetch_array($result)) {
    print_r($row);
    echo('<br>');
    }



?>