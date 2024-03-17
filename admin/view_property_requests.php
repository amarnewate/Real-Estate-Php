<?php
include("config.php");


session_start();
if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
if (isset($_SESSION['uid'])) {
    // Redirect to the login page or display an error message

}

// Retrieve all property requests
$sqlAllPropertyRequests = "SELECT pr.uid AS requester_id, u1.uname AS requester_name, u1.uemail AS requester_email, u1.uphone AS requester_phone,
                                   p.pid, p.title, u2.uid AS property_owner_id, u2.uname AS owner_name, u2.uemail AS owner_email, u2.uphone AS owner_phone,
                                   pr.message, pr.status
                            FROM property_requests pr
                            INNER JOIN property p ON pr.pid = p.pid
                            INNER JOIN user u1 ON pr.uid = u1.uid
                            INNER JOIN user u2 ON p.uid = u2.uid";

$stmtAllPropertyRequests = mysqli_prepare($con,  $sqlAllPropertyRequests);

// Check if the statement was prepared successfully
if ($stmtAllPropertyRequests === false) {
    die('Error in preparing statement: ' . mysqli_error($con)); // This will display the MySQL error message
}

// Execute the statement
mysqli_stmt_execute($stmtAllPropertyRequests);
$resultAllPropertyRequests = mysqli_stmt_get_result($stmtAllPropertyRequests);

// Close the statement
mysqli_stmt_close($stmtAllPropertyRequests);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Property Requests</title>
    <style>
        /* Your CSS styles here */

        /* Your CSS styles here */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

th, td {
    padding: 12px;
    text-align: left;
    background-color: #fff;
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
    background: blue;
}

.action-button {
    background:forestgreen;
    color: #fff;
    border: 2px solid transparent;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: border-color 1s ease;
    background-size: 200% auto;
    animation: gradientBGMove 1.5s linear infinite, rotateBorder 1s linear infinite;
}

@keyframes gradientBGMove {
    0% {
        background-position: 0% 50%;
    }
    100% {
        background-position: 100% 50%;
    }
}

@keyframes rotateBorder {
    0% {
        border-color: red;
    }
    75% {
        border-color: blue;
    }
    100% {
        border-color: green;
    }
}
    </style>
    </style>
</head>
<body>
<div class="container" id="myCard">
    <?php
    // Output all property requests
    if (mysqli_num_rows($resultAllPropertyRequests) > 0) {
        echo "<h2>All Property Requests:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Property ID</th><th>Requester Name</th><th>Requester Email</th><th>Requester Phone</th><th>Message</th><th>Owner Name</th><th>Owner Email</th><th>Owner Phone</th><th>Status</th></tr>";
        while ($row = mysqli_fetch_assoc($resultAllPropertyRequests)) {
            // Mask the last word of the message, email, and phone number with asterisks
            $messageParts = explode(" ", $row["message"]);
            $lastWordMessage = end($messageParts);
            $messageWithoutLastWord = rtrim($row["message"], " $lastWordMessage");
            $maskedMessage = $messageWithoutLastWord . str_repeat("*", strlen($lastWordMessage));

            $emailParts = explode(" ", $row["requester_email"]);
            $lastWordEmail = end($emailParts);
            $emailWithoutLastWord = rtrim($row["requester_email"], " $lastWordEmail");
            $maskedEmail = $emailWithoutLastWord . str_repeat("*", strlen($lastWordEmail));

            $phoneParts = explode(" ", $row["requester_phone"]);
            $lastWordPhone = end($phoneParts);
            $phoneWithoutLastWord = rtrim($row["requester_phone"], " $lastWordPhone");
            $maskedPhone = $phoneWithoutLastWord . str_repeat("*", strlen($lastWordPhone));



            echo "<tr>";
            echo "<td>" . $row["pid"] . "</td>";
            echo "<td>" . $row["requester_name"] . "</td>";
            echo "<td>" . $maskedEmail . "</td>";
            echo "<td>" . $maskedPhone . "</td>";
            echo "<td>" . $maskedMessage . "</td>";
            echo "<td>" . $row["owner_name"] . "</td>";
            echo "<td>" . $row["owner_email"] . "</td>";
            echo "<td>" . $row["owner_phone"] . "</td>";
            echo "<td><button class='action-button' data-property-id='" . $row["pid"] . "' data-current-status='" . $row["status"] . "' onclick='updateStatus(this)'>" . ($row["status"] === "Available" ? "Available" : "SoldOut") . "</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<h2>No property requests found.</h2>";
    }
    ?>
</div>

<script>
function updateStatus(button) {
    var propertyId = button.getAttribute("data-property-id");
    var currentStatus = button.getAttribute("data-current-status");
    var newStatus = currentStatus === "Available" ? "Unavailable" : "Available";

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
