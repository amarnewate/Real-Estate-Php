<?php
include("config.php");
$email = $_POST['email'];


$sql = "select * FROM user WHERE uemail = '{$email}'";

// print($sql);
// 
$result = mysqli_query($con, $sql);
$rowcount=mysqli_num_rows($result);
// print_r($rowcount);

if($rowcount == true)
{
	// print('yes');

	// $msg="<p class='alert alert-success'>Property Deleted</p>";
	// header("Location:feature.php?msg=$msg");

	$response['code'] = 200;
	$response['status'] = 'Your email is valid';
	echo json_encode($response);

}
else{
	$response['code'] = 400;
	$response['status'] = 'Your email is not valid';
	echo json_encode($response);
}
mysqli_close($con);

// die('22');
?>