<?php
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include("config.php");

// Redirect to login page if user is not logged in
if (!isset($_SESSION['uemail'])) {
     header("location: login.php");
     exit();
}

// Retrieve user ID if logged in
if (isset($_SESSION['uid'])) {
     $uid = $_SESSION['uid'];
} else {
     header("Location: login.php");
     exit();
}
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d h:i:s A');
// Fetch property details based on property ID (pid) from URL
if (isset($_GET['pid'])) {
     $pid = $_GET['pid'];

     // Query to fetch property details by pid
     $query = mysqli_query($con, "SELECT * FROM property WHERE pid='$pid'");
     $property = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Meta Tags -->
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="description" content="Homex template">
     <meta name="keywords" content="">
     <meta name="author" content="Unicoder">
     <link rel="shortcut icon" href="images/favicon.ico">

     <!--	Fonts
	========================================================-->
     <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

     <!--	Css Link
	========================================================-->
     <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
     <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
     <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
     <link rel="stylesheet" type="text/css" href="css/layerslider.css">
     <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
     <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
     <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
     <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
     <link rel="stylesheet" type="text/css" href="css/style.css">
     <style>
     </style>

<body>

     <div id="page-wrapper">
          <div class="row">
               <!-- Header start -->
               <?php include("include/header.php"); ?>
               <!-- Header end -->
               <div class="col-md-12">
                    <div class="tab-content mt-4" id="pills-tabContent">
                         <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home">
                              <div class="row">
                                  <?php
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];

    // Query to fetch property details by pid
    $query = mysqli_query($con, "SELECT property.*, user.uname FROM property INNER JOIN user ON property.uid=user.uid WHERE property.pid='$pid'");
    $property = mysqli_fetch_assoc($query);

    // Fetch user details based on the property owner's ID
    $property_owner_id = $property['uid'];
    $query_user = mysqli_query($con, "SELECT * FROM user WHERE uid='$uid'");
    $property_user = mysqli_fetch_assoc($query_user);
}
?>


                                        <div class="col-md-6">
                                             <div class="featured-thumb hover-zoomer mb-4">
                                                  <div class="overlay-black overflow-hidden position-relative">
                                                       <img src="admin/property/<?php echo $property['pimage']; ?>" alt="pimage">
                                                       <div class="sale bg-secondary text-white">For <?php echo $property['stype']; ?> </div>
                                                       <div class="price text-primary text-capitalize">&#8377;<?php echo $property['price']; ?> <span class="text-white"><?php echo $property['size']; ?> Sqft</span></div>
                                                  </div>
                                                  <div class="featured-thumb-data shadow-one">
                                                       <div class="p-4">
                                                            <h5 class="text-secondary hover-text-primary mb-2 text-capitalize"><a href="propertydetail.php?pid=<?php echo $property['pid']; ?>"><?php echo $property['title']; ?></a></h5>
                                                            <span class="location text-capitalize"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $property['location']; ?>&nbsp;&nbsp;PID:<?php echo $property['pid']; ?></span>
                                                       </div>
                                                       <div class="px-4 pb-4 d-inline-block w-100">
                                                            <div class="float-left text-capitalize"><i class="fas fa-user text-primary mr-1"></i>By : <?php echo $property['uname']; ?></div>
                                                            <div class="float-right"><i class="far fa-calendar-alt text-primary mr-1"></i> <?php echo date("jS F Y", strtotime($property['date'])); ?></div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="col-md-6">
    <div class="featured-thumb hover-zoomer mb-4">
        <div class="featured-thumb-data shadow-one">
            <div class="p-4">
                <h5 class="text-secondary hover-text-primary mb-2 text-capitalize">
                    <i class="fas fa-user text-primary mr-1"></i>
                    <a href="propertydetail.php?pid=<?php echo $property['pid']; ?>">
                        <?php echo $property_user['uname']; ?>
                    </a>
                </h5>
                <span class="location text-capitalize">
                    <i class="fas fa-map-marker-alt text-primary"></i>&nbsp;&nbsp;
                    Email:<?php echo $property_user['uemail']; ?>
                </span>
                <div class="pb-4 d-inline-block w-100">
                    <div class="text-capitalize">
                        <i class="fas fa-user text-primary mr-1"></i>&nbsp;&nbsp;
                        Phone: <?php echo $property_user['uphone']; ?>
                    </div>
                    <div style="text-align: center;">
    <a class="btn btn-primary d-inline-block d-none" id="Purchase_property_btn" onclick="purchaseProperty(<?php echo $property['pid']; ?>)">Purchase</a>
</div>


                </div>
                <div class="text-right">
                        <small class="text-muted" id="purchase_Time">Current Date and Time: <span id="serverTime"></span></small>
                    </div>
            </div>
        </div>
    </div>
</div>


                                        <div style="display: none;">
                                             <p id="P_pid" ><?php echo $property['pid']; ?></p>
                                             <p id="property_img" ><?php echo $property['pimage']; ?></p>
                                             <p id="price_property"><?php echo $property['price']; ?></p>
                                             <p id="user_name"> <?php echo $property_user['uname']; ?></p>
                                             <p  id="user_email"><?php echo $property_user['uemail']; ?></p>
                                             <p  id="user_phone"><?php echo $property_user['uphone']; ?></p>
                                             <p  id="property_date"><?php echo date("jS F Y", strtotime($property['date'])); ?></p>
                                             <p  id="user_uid"><?php echo $uid; ?></p> <p  id="transaction_id">Transaction_id</p>
                                             <p id="title" ><?php echo $property['title']; ?></p>
                                             <p id="invoiceNumber">purchase_Time</p>
                                             <p id="purchase_Time">purchase_Time</p>
</div>
                                   <?php  ?>
                              </div>
                         </div>
                    </div>
               </div>








          </div>
     </div>

</body>
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
$("#Purchase_property_btn").click(function() {
    // Generate a unique transaction ID
    function purchaseProperty(pid) {
        window.location.href = 'invoice.php?pid=' + pid;
    }

    // Get the price of the property from the HTML element
    var price_property = parseFloat($("#price_property").text().replace('₹', '').trim()); // Remove currency symbol and convert to float

    // Calculate GST amount
    var gst_rate = 12; // GST rate (percentage)
    var gst_amount = (price_property * gst_rate) / 100;

    // Calculate total amount including GST
    var total_amount = price_property + gst_amount;
    // Get other data needed for purchase

    var pid = $("#P_pid").text();
    var property_img = $("#property_img").text();
    var user_name = $("#user_name").text();
    var user_email = $("#user_email").text();
    var user_phone = $("#user_phone").text();
    var property_date = $("#property_date").text();
    var user_uid = $("#user_uid").text();
    var transaction_id = generateTransactionId();

    var title = $("#title").text();

    // Generate invoice number
    var invoiceNumber = generateInvoice();

    // Get the current time in UTC
    var currentTime = new Date();

    // Convert to IST (UTC+5:30)
    var ISTOffset = 330; // IST offset in minutes
    var ISTTime = new Date(currentTime.getTime() + ISTOffset * 60000);

    // Format the time string
    var purchase_Time = ISTTime.toISOString().slice(0, 19).replace('T', ' ');

    // Perform AJAX request to insert purchase data
    $.ajax({
        url: 'purchase_insertdata.php',
        type: 'POST',
        data: {
            'pid': pid,
            'property_img': property_img,
            'price_property': price_property + gst_amount,
            'user_name': user_name,
            'user_email': user_email,
            'user_phone': user_phone,
            'property_date': property_date,
            'user_uid': user_uid,
            'transaction_id': transaction_id,
            'title': title,
            'invoiceNumber': invoiceNumber,
            'purchase_Time': purchase_Time
        },
        dataType: 'json',
        success: function(result) {
            console.log('response', result);
            alert(result['msg'])
            purchaseProperty(pid);
        },
        error: function(result) {
            console.log('response', result['response'])
            alert(result['msg'])
            print_r(result);
        }
    });
});

// Function to generate a unique transaction ID
function generateTransactionId() {
    var timestamp = new Date().getTime();
    var random = Math.floor(Math.random() * 1000000);
    var txnId = '' + timestamp + random;
    // Truncate the ID if it exceeds 16 characters
    return txnId.slice(0, 16);
}

// Function to generate invoice number
function generateInvoice() {
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
var length = 12;
var invoiceId = '';

// Start with a random number
var randomNumIndex = Math.floor(Math.random() * 10);
invoiceId += chars.charAt(randomNumIndex + 52); // Adding 52 to start from numbers

for (var i = 1; i < length; i++) { // Start from index 1
    if (i % 2 === 1) { // If index is odd
        var randomAlphaIndex = Math.floor(Math.random() * 52); // 26 alphabets (uppercase and lowercase)
        invoiceId += chars.charAt(randomAlphaIndex);
    } else { // If index is even
        var randomNumIndex = Math.floor(Math.random() * 10); // 10 digits (0-9)
        invoiceId += chars.charAt(randomNumIndex + 52); // Adding 52 to start from numbers
    }
}

return invoiceId;

}


// Function to update the live date and time
function purchase_Time() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var meridiem = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // Convert 0 to 12 for 12-hour clock
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    var dateString = currentTime.toLocaleDateString();
    var timeString = hours + ':' + minutes + ':' + seconds + ' ' + meridiem;
    document.getElementById('serverTime').innerHTML = dateString + ' ' + timeString;
}

// Update live date and time every second
setInterval(purchase_Time, 1000);

// Initial call to display date and time immediately
purchase_Time();
</script>

</script>
<script>
// Get the email address
var email = "<?php echo $property_user['uemail']; ?>";

// Convert the email address to lowercase
var lowercaseEmail = email.toLowerCase();

// Update the displayed email address with the lowercase version
document.write("Email: " + lowercaseEmail);
</script>
</html>