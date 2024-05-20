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
        title VARCHAR(300) NOT NULL,
        trainingLogo boolean DEFAULT 0,
        Date_1 VARCHAR(100) DEFAULT '',
        Date_2 VARCHAR(100) DEFAULT '',
        Company VARCHAR(100) DEFAULT '',
        Address_1  VARCHAR(100) DEFAULT '',
        Address_2  VARCHAR(100) DEFAULT '',
        Post_Address_Comment  VARCHAR(100) DEFAULT '',
        Topics_Title  VARCHAR(100) DEFAULT '',
        Italics_msg  VARCHAR(100) DEFAULT '',
        Bold_last_msg  VARCHAR(100) DEFAULT '',
        Last_msg_Content  VARCHAR(100) DEFAULT '',
        Last_msg_Link  VARCHAR(100) DEFAULT ''
        )";

    $result = mysqli_query($mysqli,$sql);

    $table = 'topics';
    $sql = "CREATE TABLE  IF NOT EXISTS $table (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT(6) UNSIGNED,
    Topics_contents VARCHAR(200) NOT NULL,
    FOREIGN KEY (event_id)
        REFERENCES events(id)
        ON DELETE CASCADE
);";
    $result = mysqli_query($mysqli,$sql);

    try {
    $sql = 'INSERT INTO events (id,title,trainingLogo,Date_1,Date_2,Company,Address_1,Address_2,
    Post_Address_Comment,Topics_Title,Italics_msg,Bold_last_msg,Last_msg_Content,Last_msg_Link) 
    VALUES (1,"GE Ductless Cold Climate Training",true,"Wednesday, May 15 2024","08:00 AM to 01:00 PM","F.W. Webb Company","700 Broadway","Malden, Massachusetts 02148",
    "","Topics covered will include:","Lunch and refreshments will be provided","Space is limited...register today!","Click Here to Register","https://form.jotform.com/241015259928156") ON DUPLICATE KEY UPDATE id = values(id), title = values(title);';
    $result = mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (1,1,"• Introduction to GE Ductless") ON DUPLICATE KEY UPDATE id = values(id);';
    $result = mysqli_query($mysqli,$sql);

    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (2,1,"• Technology and Product Lineup") ON DUPLICATE KEY UPDATE id = values(id);';
    $result = mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (3,1,"• System Operation and Control") ON DUPLICATE KEY UPDATE id = values(id);';
    $result = mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (4,1,"• Troubleshooting") ON DUPLICATE KEY UPDATE id = values(id);';
    $result = mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (5,1,"• Application") ON DUPLICATE KEY UPDATE id = values(id);';
    $result = mysqli_query($mysqli,$sql);
    $result = mysqli_query($mysqli,$sql);



    // var_dump($result);



    // echo $result;  # 1
    // while ($row = $result->fetch_assoc()) {
    //     var_dump($row);
    // }
        //  Transpose data from
// | id | title | topic        |
// +----+-------+--------------+
// |  1 | TEST  | First Topic  |
// |  1 | TEST  | Second topic |
// +----+-------+--------------+
    // to
    // 1 test [First topic, Second Topic]


// }

    // echo 'This was not bad';
        }
    catch(Exception $e) {
        echo 'Message' .$e ->getMessage();
    }
//echo $result;

 // This method was doomed because I can't find a locate equiv easily

// foreach ($array as $row) {
//     // var_dump($row);
//     $isFound = false;
//     echo "Derp";
//     echo "\n";
//     var_dump($row);
//     var_dump($isFound);
//     echo "\n";
//     foreach($result as $resultrow){
//         echo 'in the nested function';
//         echo "\n";
//         echo '$result row below';
//         echo "\n";
//         var_dump($resultrow);
//         echo '$input row below';
//         echo "\n";
//         var_dump($row);
//         if ($row.id == $resultrow.id) {
//         //    result 
//         }
//     }
//     if ($isFound == false) {
//         echo "\n";
//         array_push($result,$row);
//         echo 'The value was not found';
//         echo "\n";
//     }
// }

// var_dump($result);

// var_export(array_values($result));

$array = [
    ['id' => '1','invoice_id' => '72,', 'item' => 'SN00001'],
    ['id' => '1','invoice_id' => '73,', 'item' => 'SN00002'],
    ['id' => '2','invoice_id' => '73,', 'item' => 'SN00003'],
];

$result = [];




for($i = 0; $i < count($array); $i++) {
    //echo $i. "i". "\n";
    //var_dump($array[$i]);
    $isFound = 0;
    for ($k = 0; $k < count($result); $k++) {

        if ($result[$k]['id'] == $array[$i]['id']){
            $isFound = 1;
            $result[$k]['item'];
            array_push($result[$k]['item'],$array[$i]['item']);
        }
    }
    if ($isFound ==0) {
        //   This could probably be a one liner but the current line of the array is saved as a tmp var
        //   it's value of 'item' must be converted to an array
        $tmp = $array[$i];
        $tmp_2 = array($tmp['item']);
        $tmp['item'] = $tmp_2;
        array_push($result,$tmp);
        //$result['item'] = [$array[$i]['item']];
        //  ^^ This caused un-expected behavior
    }
}

function dataTransform($mysqliReturnObject,$key,$rollup) {
    $query_result = [];
    while ($row = $mysqliReturnObject->fetch_assoc()) {
        // var_dump($row);
        array_push($query_result,$row);
        }
       
    $result = [];   
    for($i = 0; $i < count($query_result); $i++) {
    $isFound = 0;
    for ($k = 0; $k < count($result); $k++) {
        if ($result[$k][$key] == $query_result[$i][$key])    {
            $isFound = 1;
            $result[$k][$rollup];
            array_push($result[$k][$rollup],$query_result[$i][$rollup]);
            }
        }
        if ($isFound ==0) {
            $tmp = $query_result[$i];
            $tmp_2 = array($tmp[$rollup]);
            $tmp[$rollup] = $tmp_2;
            array_push($result,$tmp);
        }
}
    return $result;
}




$sql = 'select events.id,events.title,events.trainingLogo, events.Date_1,events.Date_2,events.Company, events.Address_1, events.Address_2,
        events.Post_Address_Comment,events.Topics_Title,events.Italics_msg,events.Bold_last_msg,events.Last_msg_content,events.Last_msg_link,topics.Topics_contents from events inner join topics on events.id = topics.event_id';

$mysqlEvents = mysqli_query($mysqli,$sql);
$transformedData = dataTransform($mysqlEvents,'id','Topics_contents');






//var_dump($transformedData);





// Insert into topics(id,event_id,topic) VALUES (1,1,'First Topic');
// Insert into topics(id,event_id,topic) VALUES (2,1,'Second topic');
// select events.id,events.title,topics.topic from events inner join topics on events.id = topics.event_id;

// select events.id,events.title,topics.topic from events inner join topics on events.id = topics.event_id group by topic;
// //  DELETE FROM events WHERE id=1;

//  Works
// select * from events inner join topics on events.id = topics.event_id;
// select events.id,events.title, topics.topic form events inner join topics on events.id = topics.event_id;

// select events.id,events.title,topics.topic from events inner join topics on events.id = topics.event_id;


// CREATE TABLE topics (
//     id INT(6) UNSIGNED,
//     parent_id INT(6) UNSIGNED,
//     FOREIGN KEY (parent_id)
//         REFERENCES events(id)
//         ON DELETE CASCADE
// );

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


