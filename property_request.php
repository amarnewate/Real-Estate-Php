<?php
include("config.php");

// Check if the user is logged in and retrieve their user ID
session_start();
if (!isset($_SESSION['uid'])) {
    // Redirect to the login page or display an error message
    header("Location: login.php");
    exit();
}
$loggedInUserId = $_SESSION['uid'];

// Retrieve property requests made on properties owned by the logged-in user
$sqlRequestsOnUserProperties = "SELECT pr.uid AS requester_id, u1.uname AS requester_name, u1.uemail AS requester_email, u1.uphone AS requester_phone,
                                       p.pid, p.title, u2.uid AS property_owner_id, u2.uname AS owner_name, u2.uemail AS owner_email, u2.uphone AS owner_phone,
                                       pr.message, pr.status
                                FROM property_requests pr
                                INNER JOIN property p ON pr.pid = p.pid
                                INNER JOIN user u1 ON pr.uid = u1.uid
                                INNER JOIN user u2 ON p.uid = u2.uid
                                WHERE p.uid = ? AND pr.uid != ?";

$stmtRequestsOnUserProperties = mysqli_prepare($con,  $sqlRequestsOnUserProperties);

// Check if the statement was prepared successfully
if ($stmtRequestsOnUserProperties === false) {
    die('Error in preparing statement: ' . mysqli_error($con)); // This will display the MySQL error message
}

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmtRequestsOnUserProperties, "ii", $loggedInUserId, $loggedInUserId);


// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmtRequestsOnUserProperties, "ii", $loggedInUserId, $loggedInUserId);
mysqli_stmt_execute($stmtRequestsOnUserProperties);
$resultRequestsOnUserProperties = mysqli_stmt_get_result($stmtRequestsOnUserProperties);


// Close the statement
mysqli_stmt_close($stmtRequestsOnUserProperties);
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Property Queries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }

        .action-button {
            padding: 6px 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }

        .action-button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
<div class="container" id="myCard" >

    <?php
    // Output requests made on properties owned by the logged-in user
if (mysqli_num_rows($resultRequestsOnUserProperties) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Property ID</th><th>Requester Name</th><th>Requester Email</th><th>Requester Phone</th><th>Message</th><th>Owner Name</th><th>Owner Email</th><th>Owner Phone</th><th>Status</th></tr>";
    while ($row = mysqli_fetch_assoc($resultRequestsOnUserProperties)) {
        echo "<tr>";
        echo "<td>" . $row["pid"] . "</td>";

        echo "<td>" . $row["requester_name"] . "</td>";
        echo "<td>" . $row["requester_email"] . "</td>";
        echo "<td>" . $row["requester_phone"] . "</td>";
        echo "<td>" . $row["message"] . "</td>";
        echo "<td>" . $row["owner_name"] . "</td>";
        echo "<td>" . $row["owner_email"] . "</td>";
        echo "<td>" . $row["owner_phone"] . "</td>";
        echo "<td><button class='action-button' data-property-id='" . $row["pid"] . "' data-current-status='" . $row["status"] . "' onclick='updateStatus(this)'>" . ($row["status"] === "Available" ? "Available" : "Sold Out") . "</button></td>";

    }

    echo "</table>";
} else {
    echo "<h2>No Recieved Property Queries.</h2>";
}

    ?>
</div>

<script>
function updateStatus(button) {
    var propertyId = button.getAttribute("data-property-id");
    var currentStatus = button.getAttribute("data-current-status");
    var newStatus = currentStatus === "Available" ? "Sold Out" : "Available";

    // Send AJAX request to update status
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            if (response === "success") {
                // Update button text and data attribute
                button.textContent = newStatus;
                button.setAttribute("data-current-status", newStatus);
            } else {
                alert("Failed to update status.");
            }
        }
    };
    xhr.send("property_id=" + propertyId + "&new_status=" + newStatus);
}
</script>


</body>
</html>