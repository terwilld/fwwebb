<?php

    $env = parse_ini_file('.env');

    $host =  $env["DB_HOST"];
    $user =  $env["DB_USERNAME"];
    $pass = $env["DB_PASSWORD"];
    $db = $env["DB_DATABASE"];
   
    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_errno)
        {
            printf("<br><br><br>");
            printf("Error connect: %s\n", $mysqli->connect_error);
            exit();
        }

    $table = 'events';
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        title VARCHAR(30) NOT NULL,
        trainingLogo boolean NOT NULL,
        Date_1 VARCHAR(30) NOT NULL,
        Date_2 VARCHAR(30) NOT NULL,
        Company VARCHAR(30) NOT NULL,
        Address_1  VARCHAR(30) NOT NULL,
        Address_2  VARCHAR(30) NOT NULL,
        Post_Address_Comment  VARCHAR(30) NOT NULL,
        Italics_msg  VARCHAR(30) NOT NULL,
        Bold_last_msg  VARCHAR(30) NOT NULL,
        Last_msg_Content  VARCHAR(30) NOT NULL,
        Last_msg_Link  VARCHAR(30) NOT NULL
        )";

    $result = mysqli_query($mysqli,$sql);
    echo $result;
    $sql = "Select * from pet;";

    // [
    //     'title' => 'GE Ductless Cold Climate Training', 
    //     'trainingLogo' => 'true',
    //     'Date_1'=> 'Wednesday, May 15 2024',
    //     'Date_2' => '08:00 AM to 01:00 PM',
    //     'Company' => 'F.W. Webb Company',
    //     'Address_1' => '700 Broadway',
    //     'Address_2' => 'Malden, Massachusetts 02148',
    //     'Post_Address_Comment' => '',
    //     'Topics'   => ['Title'=> 'Topics covered will include:','contents'=> ['• Introduction to GE Ductless','• Technology and Product Lineup','• Installation and Startup','• System Operation and Control','• Troubleshooting','• Application']],
    //     //'Topics'   => ['Introduction to GE Ductless','Technology and Product Lineup','Installation and Startup','System Operation and Control','Troubleshooting','Application'],
    //     'Italics_msg' => 'Lunch and refreshments will be provided',
    //     'Bold_last_msg' => 'Space is limited...register today!',
    //     'Last_msg' => ['content'=> 'Click Here to Register','link' => 'https://form.jotform.com/241015259928156']
    // ],


