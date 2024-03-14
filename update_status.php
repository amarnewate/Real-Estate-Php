<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["property_id"]) && isset($_POST["new_status"])) {
        $propertyId = $_POST["property_id"];
        $newStatus = $_POST["new_status"];

        // Update status in the database
        $sql = "UPDATE property SET status = ? WHERE pid = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $propertyId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Update status in property_requests if it exists
        $sql_request = "UPDATE property_requests SET status = ? WHERE pid = ?";
        $stmt_request = mysqli_prepare($con, $sql_request);
        mysqli_stmt_bind_param($stmt_request, "si", $newStatus, $propertyId);
        mysqli_stmt_execute($stmt_request);
        mysqli_stmt_close($stmt_request);

        // Close the database connection
        mysqli_close($con);

        // Return success message
        echo "success";
    } else {
        // Invalid request
        echo "error";
    }
} else {
    // Invalid request method
    echo "error";
}
?>
