<?php
session_start();
require("config.php");
////code

if(!isset($_SESSION['auser']))
{
	header("location:index.php");
}
$sql = "SELECT
            (SELECT COUNT(*) FROM user) AS total_users,
            (SELECT COUNT(*) FROM purchase_property) AS total_purchases,
		  (SELECT COUNT(*) FROM property ) AS total_properties,
            (SELECT COUNT(*) FROM property_requests) AS total_requests";

$result = $con->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $totalUsers = $row['total_users'];
    $totalPurchases = $row['total_purchases'];
    $totalProperty = $row['total_properties'];
    $totalRequests = $row['total_requests'];
} else {
     $con->error;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Ventura - Dashboard</title>

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">

		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">

		<link rel="stylesheet" href="assets/plugins/morris/morris.css">

		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">

		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>

		<!-- Main Wrapper -->


			<!-- Header -->
				<?php include("header.php"); ?>
			<!-- /Header -->

			<!-- Page Wrapper -->
            <div class="page-wrapper">

                <div class="content container-fluid">

					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome Admin!</h3>
								<p></p>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					<div class="row">
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-primary">
											<i class="fe fe-users"></i>
										</span>

									</div>
									<div class="dash-widget-info">

										<h3><?php echo $totalUsers;?></h3>

										<h6 class="text-muted">Users</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-primary w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-success">
											<i class="fe fe-users"></i>
										</span>

									</div>
									<div class="dash-widget-info">

										<h3><?php echo  $totalRequests;?></h3>

										<h6 class="text-muted"> Total Property Request </h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-success w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-danger">
											<i class="fe fe-users"></i>
										</span>

									</div>
									<div class="dash-widget-info">

										<h3><?php echo $totalPurchases;?></h3>

										<h6 class="text-muted">Total Property Sold</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-danger w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-warning">
											<i class="fe fe-users"></i>
										</span>

									</div>
									<div class="dash-widget-info">

										<h3><?php echo $totalProperty;?></h3>

										<h6 class="text-muted">Total Property Available</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-warning w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 col-lg-6">

							<!-- Sales Chart -->
							<div id="chart"></div>


						</div>
						<div class="col-md-12 col-lg-6">

							<!-- Invoice Chart -->
							<canvas id="chart" width="400" height="200"></canvas>



						</div>
					</div>
				</div>
			</div>
			<!-- /Page Wrapper -->


		<!-- /Main Wrapper -->

		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>

		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

		<script src="assets/plugins/raphael/raphael.min.js"></script>
		<script src="assets/plugins/morris/morris.min.js"></script>
		<script src="assets/js/chart.morris.js"></script>

		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		

    </body>

</html>
