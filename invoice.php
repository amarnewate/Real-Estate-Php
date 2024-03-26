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
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}
// Retrieve purchase_property data associated with the logged-in user
$sql = "SELECT * FROM user WHERE uid = ?";
$stmt = mysqli_prepare($con, $sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
  die('Error in preparing statement: ' . mysqli_error($con)); // This will display the MySQL error message
}

// Bind parameter to the prepared statement
mysqli_stmt_bind_param($stmt, "i", $loggedInUserId);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Get the result of the query
$result = mysqli_stmt_get_result($stmt);

// Fetch user details based on the property owner's ID
$query_user = mysqli_query($con, "SELECT * FROM user WHERE uid='$uid'");
$user = mysqli_fetch_assoc($query_user);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
  <style type="text/css">
    .receipt-content .logo a:hover {
      text-decoration: none;
      color: #7793C4;
    }

    .receipt-content .invoice-wrapper {
      background: #FFF;
      border: 1px solid #CDD3E2;
      box-shadow: 0px 0px 1px #CCC;
      padding: 40px 40px 60px;
      margin-top: 40px;
      border-radius: 4px;
    }

    #invoice {
      padding: 10px;
      background-color: grey;
    }

    .receipt-content .invoice-wrapper .payment-details span {
      color: #A9B0BB;
      display: block;
    }

    .receipt-content .invoice-wrapper .payment-details a {
      display: inline-block;
      margin-top: 5px;
    }

    .receipt-content .invoice-wrapper .line-items .print a {
      display: inline-block;
      border: 1px solid #9CB5D6;
      padding: 13px 13px;
      border-radius: 5px;
      color: #708DC0;
      font-size: 13px;
      -webkit-transition: all 0.2s linear;
      -moz-transition: all 0.2s linear;
      -ms-transition: all 0.2s linear;
      -o-transition: all 0.2s linear;
      transition: all 0.2s linear;
    }

    .receipt-content .invoice-wrapper .line-items .print a:hover {
      text-decoration: none;
      border-color: #333;
      color: #333;
    }

    .receipt-content {
      background: #ECEEF4;
      margin-bottom: 50px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      font-family: Arial, sans-serif;
      max-width: 800px;
      margin: 0 auto;
    }

    .invoice-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .invoice-header h1 {
      font-size: 28px;
      color: #333;
      margin: 0;
    }

    .invoice-details {
      margin-bottom: 30px;
    }

    .invoice-details .row {
      margin-bottom: 10px;
    }

    .invoice-details span {
      font-weight: bold;
    }

    .line-items {
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      padding: 20px 0;
      margin-bottom: 30px;
    }

    .line-items .headers {
      background-color: #f5f5f5;
      padding: 10px 0;
    }

    .line-items .row.item {
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }

    .line-items .row.item:last-child {
      border-bottom: none;
    }

    .total {
      text-align: right;
    }

    .total .extra-notes {
      margin-top: 20px;
      font-style: italic;
    }

    .print {
      text-align: center;
      margin-top: 20px;
    }

    .print a {
      background-color: #007bff;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .print a:hover {
      background-color: #0056b3;
    }

    .footer {
      text-align: center;
      margin-top: 50px;
      color: #888;
    }

    @media (min-width: 1200px) {
      .receipt-content .container {
        width: 900px;
      }
    }

    .receipt-content .logo {
      text-align: center;
      margin-top: 50px;
    }

    .receipt-content .logo a {
      font-family: Myriad Pro, Lato, Helvetica Neue, Arial;
      font-size: 36px;
      letter-spacing: .1px;
      color: #555;
      font-weight: 300;
      -webkit-transition: all 0.2s linear;
      -moz-transition: all 0.2s linear;
      -ms-transition: all 0.2s linear;
      -o-transition: all 0.2s linear;
      transition: all 0.2s linear;
    }

    .receipt-content .invoice-wrapper .intro {
      line-height: 25px;
      color: #444;
    }

    .receipt-content .invoice-wrapper .payment-info {
      margin-top: 25px;
      padding-top: 15px;
    }

    .receipt-content .invoice-wrapper .payment-info span {
      color: #A9B0BB;
    }

    .receipt-content .invoice-wrapper .payment-info strong {
      display: block;
      color: #444;
      margin-top: 3px;
    }

    @media (max-width: 767px) {
      .receipt-content .invoice-wrapper .payment-info .text-right {
        text-align: left;
        margin-top: 20px;
      }
    }

    .receipt-content .invoice-wrapper .payment-details {
      border-top: 2px solid #EBECEE;
      margin-top: 30px;
      padding-top: 20px;
      line-height: 22px;
    }


    @media (max-width: 767px) {
      .receipt-content .invoice-wrapper .payment-details .text-right {
        text-align: left;
        margin-top: 20px;
      }
    }

    .receipt-content .invoice-wrapper .line-items {
      margin-top: 40px;
    }

    .receipt-content .invoice-wrapper .line-items .headers {
      color: #A9B0BB;
      font-size: 13px;
      letter-spacing: .3px;
      border-bottom: 2px solid #EBECEE;
      padding-bottom: 4px;
    }

    .receipt-content .invoice-wrapper .line-items .items {
      margin-top: 8px;
      border-bottom: 2px solid #EBECEE;
      padding-bottom: 8px;
    }

    .receipt-content .invoice-wrapper .line-items .items .item {
      padding: 10px 0;
      color: #696969;
      font-size: 15px;
    }

    @media (max-width: 767px) {
      .receipt-content .invoice-wrapper .line-items .items .item {
        font-size: 13px;
      }
    }

    .receipt-content .invoice-wrapper .line-items .items .item .amount {
      letter-spacing: 0.1px;
      color: #84868A;
      font-size: 16px;
    }

    @media (max-width: 767px) {
      .receipt-content .invoice-wrapper .line-items .items .item .amount {
        font-size: 13px;
      }
    }

    .receipt-content .invoice-wrapper .line-items .total {
      margin-top: 30px;
    }

    .receipt-content .invoice-wrapper .line-items .total .extra-notes {
      float: left;
      width: 40%;
      text-align: left;
      font-size: 13px;
      color: #7A7A7A;
      line-height: 20px;
    }

    @media (max-width: 767px) {
      .receipt-content .invoice-wrapper .line-items .total .extra-notes {
        width: 100%;
        margin-bottom: 30px;
        float: none;
      }
    }

    @media (max-width: 767px) {
      .receipt-content .invoice-wrapper .row {
        flex-wrap: wrap;
      }

      .receipt-content .invoice-wrapper .col-sm-6 {
        width: 100%;
      }

      .receipt-content .invoice-wrapper .text-right {
        text-align: left !important;
      }

      .receipt-content .invoice-wrapper .col-xs-4,
      .receipt-content .invoice-wrapper .col-xs-3,
      .receipt-content .invoice-wrapper .col-xs-5 {
        width: 100%;
        margin-bottom: 10px;
      }
    }

    .receipt-content .invoice-wrapper .line-items .total .extra-notes strong {
      display: block;
      margin-bottom: 5px;
      color: #454545;
    }

    .receipt-content .invoice-wrapper .line-items .total .field {
      margin-bottom: 7px;
      font-size: 14px;
      color: #555;
    }

    .receipt-content .invoice-wrapper .line-items .total .field.grand-total {
      margin-top: 10px;
      font-size: 16px;
      font-weight: 500;
    }

    .receipt-content .invoice-wrapper .line-items .total .field.grand-total span {
      color: #20A720;
      font-size: 16px;
    }

    .receipt-content .invoice-wrapper .line-items .total .field span {
      display: inline-block;
      margin-left: 20px;
      min-width: 85px;
      color: #84868A;
      font-size: 15px;
    }

    .receipt-content .invoice-wrapper .line-items .print {
      margin-top: 50px;
      text-align: center;
    }



    .receipt-content .invoice-wrapper .line-items .print a i {
      margin-right: 3px;
      font-size: 14px;
    }

    .receipt-content .footer {
      margin-top: 40px;
      margin-bottom: 110px;
      text-align: center;
      font-size: 12px;
      color: #969CAD;
    }

    button {
      position: relative;

      left: -30px;
      background-color: #4CAF50;
      color: white;
      padding: 12px 15px;
    }
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"> </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body>

  <?php


  // Query to fetch data from property, user, and purchase_property tables
  $query = mysqli_query($con, "SELECT property.*, user.uname, purchase_property.*
FROM property
INNER JOIN user ON property.uid = user.uid
LEFT JOIN purchase_property ON property.pid = purchase_property.pid
WHERE property.pid = '$pid'
");


  while ($row = mysqli_fetch_array($query)) {
  ?>
    <div class="receipt-content"> <a href="index.php">Back</a>
      <div class="invoice-header">

        <h1>Invoice</h1>
      </div>
      <div class="invoice-details">
        <div class="row">
          <div class="col-sm-6 mb-4">
            <span>Transaction No.</span><br>
            <strong><?php echo $row['transaction_id']; ?></strong><br>
            <span>Invoice No.</span><br>
            <strong><?php echo $row['invoicenumber']; ?></strong>
          </div>
          <div class="col-sm-6 text-right">
            <span>Payment Date</span><br>
            <strong><?php echo $row['purchase_time']; ?></strong>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-sm-6">
            <span>From:</span><br>
            <strong><?php echo $user['uname']; ?></strong><br>
            <p><?php echo $user['uaddress']; ?><br>
            </p>
          </div>
          <div class="col-sm-6 text-right">
            <span>Payment To:</span><br>
            <strong> Homex Real Estate(India) Inc</strong>
            .<br>
            127-128, B-wing, 1st Floor, <br>
            Chintamani Plaza, Theatre.<br>
            Andheri-Kurla Road, Chakala,<br>
            Opp. CineMagic Andheri-E,<br>
            Mumbai- 400099 <br>

            </p>
          </div>
        </div>
      </div>
      <div class="line-items">
        <div class="headers clearfix">
          <div class="row">
            <div class="col-xs-4">Property Name</div>
            <div class="col-xs-3">Purchase Quantity</div>
            <div class="col-xs-5 text-right">Amount</div>
          </div>
        </div>
        <div class="items">
          <div class="row item">
            <div class="col-xs-4 desc"><?php echo $row['title']; ?></div>
            <div class="col-xs-3 qty">1</div>
            <div class="col-xs-5 amount text-right"><?php echo $row['price']; ?></div>
          </div>
        </div>
      </div>
      <?php
      $base_amount = floatval($row['price']);

      // GST rate (percentage)
      $gst_rate = 12; // Example GST rate

      // Calculate GST amount
      $gst_amount = ($base_amount * $gst_rate) / 100;

      // Total amount including GST
      $total_amount = $base_amount + $gst_amount; ?>
      <div class="total">
        <p class="extra-notes">
          Congratulations on your property purchase! We're thrilled to have you as a new homeowner.If you have any questions <br>or need assistance, feel free to reach out to us. Thank you for choosing us as your trusted real estate partner.<br> Wishing you many happy years in your new home!
        </p>
        <div class="field">Subtotal <span>&#8377; <?php echo $row['price']; ?></span></div>
        <div class="field">GST <span>12%</span></div>
        <div class="field grand-total">Total <span>&#8377; <?php echo $total_amount; ?> </span></div>
      </div>
      <div class="print">
        <a href="#" id="downloadInvoiceButton" onclick="printNow()">Print Now</a>
      </div>
      <div class="footer">
        &copy; 2024. Homex Inc.
      </div>
    </div>


    <!-- Add the required libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>


    <!-- Your JavaScript code to generate and download the PDF -->
    <script>
      function printNow() {
        window.print();
      }
    </script>

  <?php } ?>
</body>

</html>