<?php
// Set the default time zone to UTC
date_default_timezone_set('UTC');
include("config.php");
// Check if the user is logged in and retrieve their user ID
session_start();
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
} else {
    header("Location: login.php");
    exit();
}
if (isset($_GET['query'])) {
    // Sanitize the search query
    $search_query = mysqli_real_escape_string($con, $_GET['query']);
    // Query the database with the search query
    $query = mysqli_query($con, "SELECT property.pid, property.*, user.uname ,purchase_property.purchase_time,
        CASE
            WHEN COUNT(purchase_property.pid) > 1 THEN 'Old Invoice Exist Click To View'

        END AS invoice_label,
        GROUP_CONCAT(CONVERT_TZ(purchase_property.purchase_time, 'UTC', 'Asia/Kolkata') SEPARATOR ', ') AS ist_purchase_times
    FROM user
    INNER JOIN purchase_property ON user.uid =purchase_property.user_uid
    LEFT JOIN property ON purchase_property.pid = property.pid
    WHERE user.uid = '$uid' AND (purchase_property.transaction_id LIKE '%$search_query%' OR purchase_property.invoicenumber LIKE '%$search_query%')
    GROUP BY property.pid");
} else {
    // Query all invoices if no search query is provided
    $query = mysqli_query($con, "SELECT property.pid, purchase_property.purchase_time,property.*, user.uname,purchase_property.purchase_time,
        CASE
            WHEN COUNT(purchase_property.pid) > 1 THEN 'Old Invoice Exist Click To View '

        END AS invoice_label,
        GROUP_CONCAT(CONVERT_TZ(purchase_property.purchase_time, 'UTC', 'Asia/Kolkata') SEPARATOR ', ') AS ist_purchase_times
    FROM user
    INNER JOIN purchase_property ON user.uid = purchase_property.user_uid
    LEFT JOIN property ON purchase_property.pid = property.pid
    WHERE user.uid = '$uid'
    GROUP BY property.pid");
}
// Check for errors
if (!$query) {
    echo "Error: " . mysqli_error($con);
    exit;
}
// Continue with displaying the table
// Check if any invoices are found
if (mysqli_num_rows($query) > 0) {
} else {
    // No properties found, redirect to index.php with an alert
    echo "<script>alert('No properties found.'); window.location='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Invoices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-form input[type="text"] {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            margin-right: 10px;
            width: 60%;
            max-width: 300px;
        }

        .search-form button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        .invoice-list {
            list-style: none;
            padding: 0;
        }

        .invoice-item {
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .invoice-link {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }

        .invoice-link:hover {
            background-color: #f0f0f0;
        }

        .label {
            background-color: #ffc107;
            color: #333;
            padding: 4px 8px;
            margin-left: 10px;
            border-radius: 4px;
        }

        .date-tag {
            float: right;
            /* Align the date tag to the right side */
            margin-top: 5px;
            /* Add some top margin for spacing */
            font-size: 12px;
            /* Adjust the font size as needed */
            color: #666;
            /* Set the color of the date tag */
        }

        .date-tag {
            float: right;
            margin-top: 5px;
            font-size: 12px;
            color:bla;
        }
    </style>
</head>

</style>
</head>

<body>
    <div class="container">
        <h2>Property Invoices</h2>
        <!-- Search Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="search-form">
            <input type="text" id="search" name="query" placeholder="Search by Transaction or Invoice Number" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <?php if (mysqli_num_rows($query) > 0) { ?>
            <ul class="invoice-list">
                <?php while ($row = mysqli_fetch_array($query)) { ?>
                    <li class="invoice-item">
                        <a href="invoice.php?pid=<?php echo $row['pid']; ?>" class="invoice-link">
                            <?php echo $row['title']; ?>
                            <?php if (!empty($row['invoice_label'])) { ?>
                                <span class="label"><?php echo $row['invoice_label']; ?></span>
                            <?php } ?>
                            <!-- Date tag on the right side -->
                            <span class="date-tag"><?php echo date('M d, Y', strtotime($row['purchase_time'])); ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No property invoices found.</p>
        <?php } ?>
    </div>
</body>

</html>
<?php ?>