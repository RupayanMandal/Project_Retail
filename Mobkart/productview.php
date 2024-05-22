<?php

session_start();
$conn = new mysqli("localhost", "root", "", "project_retail");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$getProdCat = $_POST['category'];

//checking user query to database query
$stmt = "SELECT * FROM `product` WHERE `pcatagory` = '".$getProdCat."' AND `pavailable` > 0";
$result = $conn->query($stmt);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-3 remClass">
        <div class="card shadow rounded-0 prod-card" style="height:220px">
            <img src="'.$row['ppic'].'" class="card-img-top prod-img mx-auto mt-3" alt="...">
            <div class="card-body d-flex flex-column h-100">
              <h6 class="card-title mt-auto">'.$row['pname'].'</h6>
              <a href="products/product.php?pid='.$row['pid'].'" class="btn btn-primary middle-button">View</a>
            </div>
          </div>
        </div>';
    }
} else {
    echo "0 results";
}

$stmt = 0;
$conn->close();
