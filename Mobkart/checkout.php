<?php

session_start();


    if (isset($_POST)) {
        $conn = new mysqli("localhost", "root", "", "project_retail");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        function checkUniqueness($idToCheck){
            $conn = new mysqli("localhost", "root", "", "project_retail");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $stmt4 = "SELECT `tid` FROM `transaction` WHERE `tid` = '".$idToCheck."'";
            $res = $conn->query($stmt4);
            if($res->num_rows > 0){
                return 0;
            }else{
                return 1;
            }
        }

        function checkAvailability(){
            $conn = new mysqli("localhost", "root", "", "project_retail");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $stmt5 = "SELECT p.pid FROM cart c LEFT JOIN product p ON c.pid=p.pid WHERE c.quantity>p.pavailable AND c.uid='".$_SESSION['userid']."'";
            $res = $conn->query($stmt5);
            if($res->num_rows > 0){
                return 0;
            }else{
                return 1;
            }
        }
    
        $getUserId = $_SESSION['userid'];

        $getTransactionId = "T" . uniqid(mt_rand(), true);
        while(!checkUniqueness($getTransactionId)){
            $getTransactionId = "T" . uniqid(mt_rand(), true);
        }

        date_default_timezone_set("Asia/Calcutta");
        $date = date('Y-m-d H:i:s');


        $available = checkAvailability();
        if($available){
            $stmt = "SELECT Checkout('".$getTransactionId."', '".$date."', '".$getUserId."') AS `total`";
            $result = $conn->query($stmt);
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                header("Location: home.php?orderStatus=success&value=".$data['total']);
            }else{
                echo "failed";
            }
        }else{
            header("Location: cart.php?error=exceededstock");
        }      
}

