<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $uid = $_POST['uid'];
    $uemail = $_POST['uemail'];
    $uphone = $_POST['uphone'];
    $pid = $_POST['pid'];
    $message = $_POST['message'];
    $status = $_POST['status']; // Get the status from the form
// Validate the PID
$pid = $_POST['pid'];
$stmt = mysqli_prepare($con, "SELECT pid FROM property WHERE pid = ?");
mysqli_stmt_bind_param($stmt, "s", $pid);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if(mysqli_stmt_num_rows($stmt) == 0) {
    // If the PID doesn't exist, redirect or show an error message
    echo "Invalid Property ID. Please provide a valid Property ID.";
    exit();
}
    // Insert the property request into the database
    $sql = "INSERT INTO property_requests (uid, uemail, uphone, pid, message, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "isssss", $uid, $uemail, $uphone, $pid, $message, $status);
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Property request successfully submitted
        echo "Property request submitted successfully.";
        header("Location:index.php?");
        exit();
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($con);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    // Close the connection
    mysqli_close($con);
} else {
    // If the request method is not POST, redirect to the form page
    header("Location: property_request_form.php");
    exit();
}
?>
