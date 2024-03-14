<?php
include("config.php");
session_start();

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $uemail = $_SESSION['uemail'];
} else {
    header("Location: login.php");
    exit();
}

// Assuming you have retrieved the property request data from your database
// Define the condition for button status based on the fetched data
$sql = "SELECT * FROM property_requests WHERE uid = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch status from the database or set a default value
// For demonstration, let's assume the default status is 'enabled'
$status = 'Available';


?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Query Request </title>
    <style>


        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: black;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            position: relative;
            padding: 20px;
            color: #fff;
            background-color: #222;
            /* border-radius: 20px; */

            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }



        /* .container::after {
            filter: blur(30px);
        } */

        h2 {
            margin-top: 0;
            font-size: 24px;

            color: #fff;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #fff;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            height: 100px;
        }

        /* input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        } */

        input[type="submit"]::after::before {
            content: "";

            background: green;
            position: absolute;

            animation: animated-shadow 10s linear infinite alternate

        }


        input[type="submit"] {
            background: green;
            color: #fff;
            border: 2px solid transparent; /* Initial transparent border */
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: border-color 0.5s ease;
            background-size: 200% auto;
            animation: animated-shadow 10s linear infinite alternate;
        }

        @keyframes animated-shadow {
    0% {
      background-position: 0 0;
    }

    50% {
      background-position: 100% 0;
    }

    100% {
      background-position: 0 0;
    }
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
<body>
<div class="container">
    <h2>Send Query Request</h2>
    <form action="submit_property_requests.php" method="post">
        <label for="uid"> Your ID:</label>
        <input type="text" id="uid" name="uid" value="<?php echo $uid; ?>" readonly>
        <label for="uemail">Email:</label>
        <input type="email" id="uemail" name="uemail"  value="<?php echo isset($uemail) ? $uemail : ''; ?>" required readonly>
        <label for="uphone">Phone:</label>
        <input type="text" id="uphone" name="uphone"minlength="10" maxlength="10"required>
        <label for="pid">Property ID:</label>
        <input type="text" id="id" name="pid" value="" required>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea>
        <!-- Add a hidden input field for the status -->
        <input type="hidden" name="status" value="<?php echo $status; ?>">

        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>
