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

// Retrieve all property requests made by the logged-in user
$sqlUserPropertyRequests = "SELECT pr.id, pr.uid AS requester_id, pr.pid, u.uemail AS requester_email, u.uphone AS requester_phone, pr.message, pr.status
                            FROM property_requests pr
                            INNER JOIN user u ON pr.uid = u.uid
                            WHERE pr.uid = ?";
                            
$stmtUserPropertyRequests = mysqli_prepare($con, $sqlUserPropertyRequests);

// Check if the statement was prepared successfully
if ($stmtUserPropertyRequests === false) {
    die('Error in preparing statement: ' . mysqli_error($con));
}

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmtUserPropertyRequests, "i", $loggedInUserId);
mysqli_stmt_execute($stmtUserPropertyRequests);
$resultUserPropertyRequests = mysqli_stmt_get_result($stmtUserPropertyRequests);

// Close the statement
mysqli_stmt_close($stmtUserPropertyRequests);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Sended Query</title>
    <style>
        /* Styles here */


        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-button, .show-details-button {
  background-color: #45a049;
            border: 2px solid transparent; /* Initial transparent border */
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: border-color 1s ease;
            background-size: 200% auto;

        }





        .action-button:hover, .show-details-button:hover {
            background-color: #45a049;
        }

        .hidden {
            display: none;
        }


    </style>
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/layerslider.css">
    <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
</head>
<body>


<div id="page-wrapper">
        <div class="row">
            <!--	Header start  -->
            <?php include("include/header.php"); ?>
            <!--	Header end  -->
<div class="container">
    <h2>Sended Property Queries:</h2>
    <?php if (mysqli_num_rows($resultUserPropertyRequests) > 0): ?>
        <table>
            <tr>
                <th>Request ID</th>
                <th>Property ID</th>
                <th>Your Email</th>
                <th>Your Phone</th>
                <th>Message</th>
                <th>Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($resultUserPropertyRequests)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['pid']; ?></td>
                    <td><?php echo $row['requester_email']; ?></td>
                    <td><?php echo $row['requester_phone']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td>
                        <?php
                        // Check if the status is enabled or disabled
                        $button_class = $row['status'] === 'enabled' ? 'action-button' : 'action-button disabled';
                        // Echo the button with data attributes
                        echo "<button class='$button_class' data-property-id='" . $row["pid"] . "' data-current-status='" . $row["status"] . "' onclick='updateStatus(this)'>" . ($row["status"] === "Available" ? "Sold Out " : "Available") . "</button>";
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No Sended Property Query found.</p>
    <?php endif; ?>



</div>
</body>
</html>
