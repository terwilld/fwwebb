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
        events.Post_Address_Comment,events.Topics_Title,events.Italics_msg,events.Bold_last_msg,events.Last_msg_content,events.Last_msg_link,topics.Topics_contents from events left outer join topics on events.id = topics.event_id';
try {
$mysqlEvents = mysqli_query($mysqli,$sql);
$transformedData = dataTransform($mysqlEvents,'id','Topics_contents');
} catch(Exception $e) {
    dataLoad();
    $mysqlEvents = mysqli_query($mysqli,$sql);
    $transformedData = dataTransform($mysqlEvents,'id','Topics_contents');
}



//var_dump($transformedData);

function dataLoad () {    
    
    $env = parse_ini_file('.env');
    $host =  $env["DB_HOST"];
    $user =  $env["DB_USERNAME"];
    $pass = $env["DB_PASSWORD"];
    $db = $env["DB_DATABASE"];
    $mysqli = new mysqli($host, $user, $pass, $db);  
    $table = 'events';
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        title VARCHAR(300) NOT NULL,
        trainingLogo VARCHAR(300) NOT NULL,
        Date_1 VARCHAR(100) DEFAULT '',
        Date_2 VARCHAR(100) DEFAULT '',
        Company VARCHAR(100) DEFAULT '',
        Address_1  VARCHAR(100) DEFAULT '',
        Address_2  VARCHAR(100) DEFAULT '',
        Post_Address_Comment  VARCHAR(300) DEFAULT '',
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
    $sql = 'INSERT INTO events (id,title,trainingLogo,Date_1,Date_2,Company,Address_1,Address_2,Post_Address_Comment,Topics_Title,Italics_msg,Bold_last_msg,Last_msg_Content,Last_msg_Link) 
    VALUES 
    (1,"GE Ductless Cold Climate Training","true","Wednesday, May 15 2024","08:00 AM to 01:00 PM","F.W. Webb Company","700 Broadway","Malden, Massachusetts 02148",
    "","Topics covered will include:","Lunch and refreshments will be provided","Space is limited...register today!","Click Here to Register","https://form.jotform.com/241015259928156") ON DUPLICATE KEY UPDATE id = values(id), title = values(title);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (1,1,"• Introduction to GE Ductless") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (2,1,"• Technology and Product Lineup") ON DUPLICATE KEY UPDATE id = values(id);';
     mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (3,1,"• System Operation and Control") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (4,1,"• Troubleshooting") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (5,1,"• Application") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    mysqli_query($mysqli,$sql);
           
    #2
    $sql = 'INSERT INTO events (id,title,trainingLogo,Date_1,Date_2,Company,Address_1,Address_2,Post_Address_Comment,Topics_Title,Italics_msg,Bold_last_msg,Last_msg_Content,Last_msg_Link) 
    VALUES 
    (2,"Understanding & How to Troubleshoot Air Flow Class","true","Thursday, May 16 2024","08:00 AM to 10:00 AM","F.W. Webb Company","261 Route 46 West",
"Elmwood Park, New Jersey 07407","Join F.W. Webb\'s in-house HVAC/R tech for this informative class on understanding and troubleshooting air flow.",
"Key topics include:","Bagels and coffee will be provided","Space is limited...register today!","Click Here to Register","https://form.jotform.com/241073360477152") ON DUPLICATE KEY UPDATE id = values(id), title = values(title);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (6,2,"• How to take & understand static pressures") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (7,2,"• Proper procedure for taking static pressure") ON DUPLICATE KEY UPDATE id = values(id);';
     mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (8,2,"• Diagnosing issues using airflow readings") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (9,2,"• Understanding what problems arise with improper airflow") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);

    #3
    $sql = 'INSERT INTO events (id,title,trainingLogo,Date_1,Date_2,Company,Address_1,Address_2,
    Post_Address_Comment,Topics_Title,Italics_msg,Bold_last_msg,Last_msg_Content,Last_msg_Link) 
    VALUES 
    (3,"Lunch & Learn with Ideal Heating","true","Thursday, May 16 2024","11:30 AM to 01:30 PM","F.W. Webb Company","30 Pomeroy Ave.","Meriden, Connecticut 06450"
    ,"Join us for an upcoming Lunch & Learn at F.W. Webb in Meriden, CT to see the latest and greatest from Ideal Heating NA.","","","","","") ON DUPLICATE KEY UPDATE id = values(id), title = values(title);';
    mysqli_query($mysqli,$sql);

    #4
    $sql = 'INSERT INTO events (id,title,trainingLogo,Date_1,Date_2,Company,Address_1,Address_2,
    Post_Address_Comment,Topics_Title,Italics_msg,Bold_last_msg,Last_msg_Content,Last_msg_Link) 
    VALUES 
    (4,"NAVAC Best Practices for Refrigerant Evacuation and Recovery","true","Thursday, May 16 2024","04:00 PM to 08:00 PM", "BADSONS Beer Co.","251 Roosevelt Drive","Derby, Connecticut 06418",
    "Join F.W. Webbs in-house HVAC/R tech for this informative class on understanding and troubleshooting air flow.",
    "Topics to be covered include:","Food and drinks will be provided", "Space is limited...register today!","Click Here to Register",
    "https://form.jotform.com/241153449759162") ON DUPLICATE KEY UPDATE id = values(id), title = values(title);';
    mysqli_query($mysqli,$sql);

    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (10,4,"") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (11,4,"<b>Evacuation Explained</b>") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (12,4,"Do you have a pump or a paperweight? Make sure your tools are working for you, and develop a firm grasp on the best practices for the evacuation of HVAC/R equipment") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (13,4,"") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (14,4,"<b>Recovery Demystified</b>") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);
    $sql = 'INSERT into topics(id,event_id,Topics_contents) VALUES (15,4,"Have your cake and eat it too - achieve true and complete recovery of ALL liquid and vapor faster and more efficient than ever before.") ON DUPLICATE KEY UPDATE id = values(id);';
    mysqli_query($mysqli,$sql);


//'Topics_contents' => ['','<b>Evacuation Explained</b>','','','<b>Recovery Demystified</b>','Have your cake and eat it too - achieve true and complete recovery of ALL liquid and vapor faster and more efficient than ever before.'],
        



        }
    catch(Exception $e) {
        echo 'Message' .$e ->getMessage();
    }
 }



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


    // var_dump($result);

        //
        //  Testing functions pre wrapping in function and adding variable for roll up field
        // 

// for($i = 0; $i < count($array); $i++) {
//     //echo $i. "i". "\n";
//     //var_dump($array[$i]);
//     $isFound = 0;
//     for ($k = 0; $k < count($result); $k++) {

//         if ($result[$k]['id'] == $array[$i]['id']){
//             $isFound = 1;
//             $result[$k]['item'];
//             array_push($result[$k]['item'],$array[$i]['item']);
//         }
//     }
//     if ($isFound ==0) {
//         //   This could probably be a one liner but the current line of the array is saved as a tmp var
//         //   it's value of 'item' must be converted to an array
//         $tmp = $array[$i];
//         $tmp_2 = array($tmp['item']);
//         $tmp['item'] = $tmp_2;
//         array_push($result,$tmp);
//         //$result['item'] = [$array[$i]['item']];
//         //  ^^ This caused un-expected behavior
//     }
// }


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