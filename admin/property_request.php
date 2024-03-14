<?php
include("config.php");

// Retrieve all property requests
$sqlAllRequests = "SELECT pr.uid AS requester_id, u1.uname AS requester_name, u1.uemail AS requester_email, u1.uphone AS requester_phone,
                        p.pid, p.title, u2.uid AS property_owner_id, u2.uname AS owner_name, u2.uemail AS owner_email, u2.uphone AS owner_phone,
                        pr.message
                    FROM property_requests pr
                    INNER JOIN property p ON pr.pid = p.pid
                    INNER JOIN user u1 ON pr.uid = u1.uid
                    INNER JOIN user u2 ON p.uid = u2.uid";

$stmtAllRequests = mysqli_prepare($con, $sqlAllRequests);

// Check if the statement was prepared successfully
if ($stmtAllRequests === false) {
    die('Error in preparing statement: ' . mysqli_error($con));
}

// Execute the statement
mysqli_stmt_execute($stmtAllRequests);
$resultAllRequests = mysqli_stmt_get_result($stmtAllRequests);

// Output all property requests
if (mysqli_num_rows($resultAllRequests) > 0) {
    echo "<h2>All Property Requests:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Property ID</th><th>Requester Name</th><th>Requester Email</th><th>Requester Phone</th><th>Message</th><th>Owner Name</th><th>Owner Email</th><th>Owner Phone</th></tr>";
    while ($row = mysqli_fetch_assoc($resultAllRequests)) {
        echo "<tr>";
        echo "<td>" . $row["pid"] . "</td>";
        echo "<td>" . $row["requester_name"] . "</td>";
        echo "<td>" . $row["requester_email"] . "</td>";
        echo "<td>" . $row["requester_phone"] . "</td>";
        echo "<td>" . $row["message"] . "</td>";
        echo "<td>" . $row["owner_name"] . "</td>";
        echo "<td>" . $row["owner_email"] . "</td>";
        echo "<td>" . $row["owner_phone"] . "</td>";
        echo "<td><button class='action-button'>Buy</button></td>";
        echo "</tr>";
    } 
    echo "</table>";
} else {
    echo "<h2>No property requests found.</h2>";
}

// Close the statement
mysqli_stmt_close($stmtAllRequests);
mysqli_close($con);
?>
