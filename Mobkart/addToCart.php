<?php
session_start();

// Database connection


$con = new mysqli("localhost", "root", "", "project_retail");
//print_r($_SESSION['pname']);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['quantity']) && isset($_SESSION['pid']) &&isset($_SESSION['userid'])) {
        $product_id = $_SESSION['pid'];
        $user_id=$_SESSION['userid'];
        $quantity = $_POST['quantity'];

        // Sanitize inputs
        $user_id= mysqli_real_escape_string($con, $user_id);
        $product_id = mysqli_real_escape_string($con, $product_id);
        $quantity = mysqli_real_escape_string($con, $quantity);

        // Insert into the cart table
        $check_sql = "SELECT quantity FROM cart WHERE pid = '$product_id' AND uid = '$user_id'";
        $result = $con->query($check_sql);

        if ($result->num_rows > 0) {
            // Product exists, update the quantity
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;

            $update_sql = "UPDATE cart SET quantity = '$new_quantity' WHERE pid = '$product_id' AND uid='$user_id'";
            if ($con->query($update_sql) === TRUE) {
                $_SESSION['message']= "Cart updated successfully!";
            } else {
                $_SESSION['message']= "Error updating cart: " . $con->error;
            }
        } else {
            // Product does not exist, insert a new entry
            $insert_sql = "INSERT INTO cart (uid,pid, quantity) VALUES ('$user_id','$product_id', '$quantity')";
            if ($con->query($insert_sql) === TRUE) {
                $_SESSION['message']= "Added to cart successfully!";
            } else {
                $_SESSION['message']= "Error: " . $insert_sql . "<br>" . $con->error;
            }
        }
        header("Location: products/product.php?pid=".$product_id."&addStatus=success");
    } else {
        echo "Please ensure you are logged in and all fields are filled out.";
    }
}

$con->close();
?>
