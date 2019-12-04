<?php

$my_host = "localhost";
$my_user = "root";
$my_pwd = "password";
$db = "driver";

$conn = mysqli_connect($my_host, $my_user, $my_pwd, $db);

if($conn){
    //echo "success connection";
} else{
    echo "failed to connect";
}


try {
    $pdo_conn = new PDO("mysql:host=$my_host;dbname=$db", $my_user, $my_pwd);
} catch (PDOException $e) {
    echo 'Not Connected to Server : ';
    echo $e->getMessage();
}
$pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo_conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



?>
