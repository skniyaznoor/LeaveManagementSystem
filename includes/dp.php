<?php
    $HOSTNAME = "127.0.0.1";
    $USERNAME = "root";
    $PASSWORD = "";
    $DBNAME = "leave_management";

    try{
        $conn = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DBNAME);
        if($conn->connect_error){
            echo "Connection Error".$conn->connect_error;
        } else {
            // echo "Connection Successful";
        }
    } catch (Exception $e){
        echo "Check Credentials<br>";
        echo $e->getMessage()." at line ".$e->getLine();
    }
?>