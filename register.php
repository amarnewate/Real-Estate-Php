<?php
include("config.php");
require 'vendor/autoload.php';

use libphonenumber\PhoneNumberUtil;

// Initialize PhoneNumberUtil
$phoneUtil = PhoneNumberUtil::getInstance();
$arrRegions = $phoneUtil->getSupportedRegions();

// Define error and message variables
$error = "";
$msg = "";

// Check if form is submitted

if (isset($_POST['reg'])) {
    // Check if the country code is selected
    if (empty($_POST['country'])) {
        $error = "<p class='alert alert-danger'>Please select a country code</p>";
    } else {
        // Extract phone number and country code from form input
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        $phoneWithCountryCode = $country . $phone;
        $defaultRegion = $phoneUtil->getRegionCodeForCountryCode($country);
        $error = "";
        // Parse the phone number
        $numberProto = $phoneUtil->parse($phoneWithCountryCode, $defaultRegion);

        // Validate the phone number
        $isValid = $phoneUtil->isValidNumber($numberProto);

        // Check if the phone number is valid
        if (!$isValid) {
            // If number is not valid, set error message and prevent form submission
            $error = "Phone number is not valid";
        } else {
            // If number is valid, proceed with form submission logic


            $name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $phone = $phoneWithCountryCode;
            $address = $_REQUEST['address'];
            $pass = $_REQUEST['pass'];
            $utype = $_REQUEST['utype'];

            $uimage = $_FILES['uimage']['name'];
            $temp_name1 = $_FILES['uimage']['tmp_name'];

            $query = "SELECT * FROM user where uemail='$email' AND uphone='$phone'";
            $res = mysqli_query($con, $query);
            $num = mysqli_num_rows($res);

            if ($num == 1) {
                $error = "<p class='alert alert-warning'>Email Id or Phone Number already Exist</p> ";
            } else {
                if (!empty($name) && !empty($email) && !empty($phone) && !empty($pass) && !empty($uimage)) {
                    // Hash the password
                    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO user (uname,uemail,uphone,uaddress,upass,utype,uimage) VALUES ('$name','$email','$phone','$address','$hashed_password','$utype','$uimage')";
                    $result = mysqli_query($con, $sql);
                    move_uploaded_file($temp_name1, "admin/user/$uimage");

                    if ($result) {
                        $msg = "<p class='alert alert-success'>Register Successfully</p> ";
                        // JavaScript code to redirect after 3 seconds
                        echo "<script>
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 3000);
        </script>";
                    } else {
                        $error = "<p class='alert alert-warning'>Register Not Successfully</p> ";
                    }
                } else {
                    $error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
                }
            }
    }
}
} else {
    // Clear error message if the form is not submitted
    $error = "";
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
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <style>
        #country {
            width: 7rem;
            display: flex;
            position: absolute;
            font-size: 10px;
            z-index: 2;
        }

        #phone {
            width: 63%;
            display: flex;
            position: relative;
            left: 7.5rem;
            z-index: 1;
        }
    </style>
    <!--	Title
	=========================================================-->
    <title>Homex - Real Estate Template</title>
</head>

<body>

    <!--	Page Loader
=============================================================
<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>
-->


    <div id="page-wrapper">
        <div class="row">
            <!--	Header start  -->
            <?php include("include/header.php"); ?>
            <!--	Header end  -->

            <!--	Banner   --->
            <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Register</b></h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb" class="float-left float-md-right">
                                <ol class="breadcrumb bg-transparent m-0 p-0">
                                    <li class="breadcrumb-item text-white"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Register</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Banner   --->



            <div class="page-wrappers login-body full-row bg-gray">
                <div class="login-wrapper">
                    <div class="container">
                        <div class="loginbox">
                            <div class="login-right">
                                <div class="login-right-wrap">
                                    <h1>Register</h1>
                                    <p class="account-subtitle">Access to our dashboard</p>
                                    <?php echo $error; ?><?php echo $msg; ?>
                                    <!-- Form -->
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" placeholder="Your Fullname*" minlength="4">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control" placeholder="Your Email*" autocomplete="on">
                                        </div>
                                        <div class="form-group">
                                            <!-- Country dropdown -->
                                            <select id="country" class="form-control" name="country">
                                                <option disabled="disabled" selected="selected">Country Code</option>

                                                <?php foreach ($arrRegions as $region) {
                                                    echo "<option |value=" . $region . ">+" . $phoneUtil->getCountryCodeForRegion($region) . "</option>";
                                                } ?>
                                            </select>
                                            <!-- Phone number input -->
                                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Your Phone*" minlength="10" maxlength="15" oninput="checkInput(this)">
                                            <span id="phone-error" class="error-message"></span>

                                        </div>

                                        <div class="form-group">
                                            <input type="text" name="address" class="form-control" placeholder="Your Address*" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" name="pass" class="form-control" placeholder="Your Password*" autocomplete="on">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility(this)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="utype" value="user" checked>User
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="utype" value="agent">Agent
                                            </label>
                                        </div>
                                        <div class="form-check-inline disabled">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="utype" value="builder">Builder
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label"><b>User Image*</b></label>
                                            <input class="form-control" name="uimage" type="file">
                                        </div>

                                        <button class="btn btn-primary" name="reg" value="Register" type="submit">Register</button>

                                    </form>

                                    <div class="login-or">
                                        <span class="or-line"></span>
                                        <span class="span-or">or</span>
                                    </div>

                                    <!-- Social Login -->
                                    <!-- <div class="social-login">
									<span>Register with</span>
									<a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
									<a href="#" class="google"><i class="fab fa-google"></i></a>
									<a href="#" class="facebook"><i class="fab fa-twitter"></i></a>
									<a href="#" class="google"><i class="fab fa-instagram"></i></a>
								</div> -->
                                    <!-- /Social Login -->

                                    <div class="text-center dont-have">Already have an account? <a href="login.php">Login</a></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--	login  -->


            <!--	Footer   start-->
            <?php include("include/footer.php"); ?>
            <!--	Footer   start-->

            <!-- Scroll to top -->
            <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a>
            <!-- End Scroll To top -->
        </div>
    </div>
    <script>
        const password = document.querySelector('input[type="password"]');

        password.addEventListener("focus", (event) => {
            event.target.style.background = "#f2f2f2";
        });

        password.addEventListener("blur", (event) => {
            event.target.style.background = "";

        });
    </script>
    <script>
        function togglePasswordVisibility(button) {
            var passwordInput = button.parentElement.parentElement.querySelector('input[name="pass"]');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                button.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
                passwordInput.type = "password";
                button.innerHTML = '<i class="fa fa-eye"></i>';
            }
        }

        function checkInput(input) {
            // Remove non-numeric characters
            input.value = input.value.replace(/[^0-9]/g, '');

            // Check if the input contains any non-numeric characters
            if (input.value.match(/[^0-9]/)) {
                // Display error message
                document.getElementById("phone-error").textContent = "Only numeric characters are allowed.";
                // Clear input
                input.value = "";
            } else {
                // Clear error message
                document.getElementById("phone-error").textContent = "";
            }
        }
    </script>
    <!-- Wrapper End -->

    <!--	Js Link
============================================================-->
    <script src="js/jquery.min.js"></script>
    <!--jQuery Layer Slider -->
    <script src="js/greensock.js"></script>
    <script src="js/layerslider.transitions.js"></script>
    <script src="js/layerslider.kreaturamedia.jquery.js"></script>
    <!--jQuery Layer Slider -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/tmpl.js"></script>
    <script src="js/jquery.dependClass-0.1.js"></script>
    <script src="js/draggable-0.1.js"></script>
    <script src="js/jquery.slider.js"></script>

    <script src="js/wow.js"></script>
    <script src="js/custom.js"></script>
    <script src="numbervalidation.js"></script>
</body>

</html>