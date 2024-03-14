<?php
include("config.php");

if (isset($_POST["pid"])) {
    // Assign POST parameters to variables
    $pid = $_POST["pid"];
    $property_img = $_POST["property_img"];
    $price_property = $_POST["price_property"];
    $user_name = $_POST["user_name"];
    $user_email = $_POST["user_email"];
    $user_phone = $_POST["user_phone"];
    $property_date = $_POST["property_date"];
    $user_uid = $_POST["user_uid"];
    $transaction_id = $_POST["transaction_id"];
    $title = $_POST["title"];
    $invoiceNumber = $_POST["invoiceNumber"];
    $purchase_Time = $_POST["purchase_Time"];

    // Prepare and execute the SQL query using prepared statements
    $sql = "INSERT INTO purchase_property (pid, property_img, price_property, user_name, user_email, user_phone, property_date, user_uid, transaction_id, title, invoiceNumber, purchase_Time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssss", $pid, $property_img, $price_property, $user_name, $user_email, $user_phone, $property_date, $user_uid, $transaction_id, $title, $invoiceNumber, $purchase_Time);
    $result = mysqli_stmt_execute($stmt);

    // Check if the query was successful and return appropriate response
    if ($result) {
        $response = array(
            'msg' => 'Property purchase successful!',
            'code' => 200
        );
    } else {
        $response = array(
            'msg' => 'Failed to insert data into the database',
            'code' => 500,
        );
    }

    // Set the correct Content-Type header and output the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
