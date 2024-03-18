<?php
session_start();
include("config.php");
$error="";
$msg="";
if(isset($_REQUEST['login']))
{
	$email=$_REQUEST['email'];
	$pass=$_REQUEST['pass'];
if(!empty($email) && !empty($pass))
	{
		$sql = "SELECT * FROM user where uemail='$email' && upass='$pass'";
		$result=mysqli_query($con, $sql);
		$row=mysqli_fetch_array($result);
		   if($row){
			$_SESSION['uid']=$row['uid'];
				$_SESSION['uemail']=$email;
				header("location:index.php");
	   }
		   else{
			   $error = "<p class='alert alert-warning'>Login Not Successfully</p> ";
		   }
	}else{
		$error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
	}
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
		<?php include("include/header.php");?>
        <!--	Header end  -->
       <!--	Banner   --->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Login</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Login</li>
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
        <h1>Login</h1>
        <p class="account-subtitle">Access to our dashboard</p>
        <?php echo $error; ?><?php echo $msg; ?>
        <!-- Form -->
        <form method="post">
            <div class="form-group">
                <input type="email"  name="email" class="form-control" placeholder="Your Email*" autocomplete="email">
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input type="password" name="pass"  class="form-control" placeholder="Your Password" autocomplete="passowrd" id="password">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" name="login" value="Login" type="submit">Login</button>
            <a class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="color:white;">Forgot Password</a>
            <!-- onclick="forgotpassword()" -->
        </form>
							<div class="login-or">
									<span class="or-line"></span>
									<span class="span-or">or</span>
								</div>
							<div class="text-center dont-have">Don't have an account? <a href="register.php">Register</a></div>
						</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<!--	login  -->
       <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
       <!-- Scroll to top -->
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a>
        <!-- End Scroll To top -->
    </div>
</div>
<!-- Wrapper End -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var icon = document.querySelector('#togglePassword i');
       // Toggle the type attribute
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form method="post">
			<div class="form-group">
				<input type="email"  name="forgotpass" id="forgotpass" class="form-control" placeholder="Enter Your Email*">
			</div>
			<div class="form-group d-none" id="npasstext">
				<input type="type"  name="newpass" id="newpass" class="form-control" placeholder="Enter Your Password*">
			</div>
	</form>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary" onclick="forgotpassword()" id="upd_btn">Update</button>
      	<button type="button" class="btn btn-primary d-none" onclick="change_password()" id="cha_btn">Change Password</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
     </div>
    </div>
  </div>
</div>
<!-- model end -->
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
<script type="text/javascript">
	// $( document ).ready(function() {
   function forgotpassword() {
    	
		var emailid=$("#forgotpass").val();
		var resultobj;
		console.log('emailid',emailid)
    	$.ajax({
	        type: "POST",
	        url: "forgotpassword.php",
	        data: {'email': emailid},
	        dataType: 'json',
	        async: !1,
	         success: function (data) {
	                resultobj = data;
	            },
	            error: function (textStatus, errorThrown) {
	               alert('error');
	            }
    	});
   // return resultobj;
   	console.log('resultobj',resultobj)
   	if (resultobj['code']==200) {
    		// alert('yes')
    		$('#forgotpass').prop('readonly', true);
    		 $("#npasstext").addClass('d-block').siblings().removeClass('d-none');
    		 $("#upd_btn").hide()
    		 $("#cha_btn").addClass('d-block').siblings().removeClass('d-none');
   	}else{
    		alert('something went wrong! check your email id')
    	}
   }
   function change_password() {
    	// alert('change_password'):
    	var emailid=$("#forgotpass").val();
    	var pass=$("#newpass").val();
		var resultobj;
    	$.ajax({
	        type: "POST",
	        url: "changepassword.php",
	        data: {'email': emailid,'pass': pass},
	        dataType: 'json',
	        async: !1,
	         success: function (data) {
	                resultobj = data;
	            },
	            error: function (textStatus, errorThrown) {
	               alert('error');
	            }
    	});
   	console.log('resultobj--change passowrd',resultobj)
    	if (resultobj['code']==200) {
   		location.reload();
    		$('#exampleModal').modal('hide');
    		alert('User Password has been changes successfully!')
    	}else{
    		alert('something went wrong! check your email id')
    	}
    }
// });
</script>
</body>
</html>