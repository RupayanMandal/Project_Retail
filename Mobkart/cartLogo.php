<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project_retail");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Retrieve the user ID from the session
$curuid = $_SESSION['userid']; // Example user ID

// Step 3: Retrieve the quantity input from the HTML form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quan = $_POST["quantity"]; // Assuming your input field is named "quantity"
} else {
    // Handle if form not submitted
    $quan = 0; // Set default quantity
}

// Step 4: Execute the InsertIntoCart function
//$curpid = $_SESSION['pid']; // Example product ID

// Insert the item into the cart table


    // Step 5: Calculate the sum of quantities for each unique user ID across different product IDs
    $sql_sum = "SELECT COUNT(*) as numItem from cart where uid='$curuid'";
    $result_sum = $conn->query($sql_sum);

    if ($result_sum->num_rows > 0) {
        echo $result_sum->fetch_assoc()['numItem'];
    } else {
        echo "No data found.";
    }

// Close the database connection
$conn->close();
?>
