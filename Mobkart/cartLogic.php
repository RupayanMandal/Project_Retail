<?php

session_start();

    if (isset($_POST)) {
        $conn = new mysqli("localhost", "root", "", "project_retail");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $getUserId = $_SESSION['userid'];
        $getProdId = $_POST["prodId"];

        if ($_POST['op'] == "minus"){
            //checking user query to database query
            $stmt = "SELECT UpdateCart('".$getUserId."', '".$getProdId."', -1)";
            $result = $conn->query($stmt);
            if ($result->num_rows > 0) {
                echo "success";
                /* $Sstmt = "SELECT * FROM `cart` WHERE `uid` = '".$getUserId."'";
                $Sresult = $conn->query($Sstmt);
                if ($Sresult->num_rows > 0) {
                // Output data of each row
                    while($row = $Sresult->fetch_assoc()) { 
                            $stmt2 = "SELECT * FROM `product` WHERE `pid` = '".$row['pid']."'";
                            $prodResult = $conn->query($stmt2);
                            $prod = $prodResult->fetch_assoc();
                            $cost = $prod['pprice']*$row['quantity'];
                            echo <<<CardSegment
                                <div class="card rounded-3 mb-4 remResult">
                                <div class="card-body p-4">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                    <img src="{$prod['ppic']}" class="img-fluid rounded-3" alt="{$prod['pname']}">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3">
                                    <p class="lead fw-normal mb-2">{$prod['pname']}</p>
                                    <p><span class="text-muted">Category: </span>{$prod['pcatagory']}</p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                        <i class="bi bi-dash fs-4 min"  value="less" id="{$prod['pid']}"></i>
                                    </button>
                        
                                    <input id="{$prod['pid']}" min="0" name="quantity" value="{$row['quantity']}" type="number"
                                        class="form-control valList"/>
                        
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()" value="add" class="add">
                                        <i class="bi bi-plus fs-4"></i>
                                    </button>
                                    </div>
                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                    <h5 class="mb-0">Rs. {$cost}.00</h5>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                    <i class="bi bi-trash-fill fs-3 text-danger" value="remove"></i>
                                    </div>
                                </div>
                                </div>
                            </div>
                            CardSegment;
                }} else {
                echo "0 results"; 
            }*/
            } else {
                echo "nope";
            }
    
            $stmt = 0;
            $conn->close();
        }elseif($_POST['op'] == "plus"){
            $stmt = "SELECT UpdateCart('".$getUserId."', '".$getProdId."', 1)";
            $result = $conn->query($stmt);
            if ($result->num_rows > 0) {
                echo "success";
            } else {
                echo "nope";
            }
                
            $stmt = 0;
            $conn->close();
            
        }elseif($_POST['op'] == "remove"){
            $stmt = "DELETE FROM `cart` WHERE `uid` = '".$getUserId."' AND `pid` = '".$getProdId."'";
            $result = $conn->query($stmt);
            if ($result->num_rows > 0) {
                echo "success";
            } else {
                echo "nope";
            }

                
            $stmt = 0;
            $conn->close();
    
        }
}

