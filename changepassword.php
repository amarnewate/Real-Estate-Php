<?php
include("config.php");

$email = mysqli_real_escape_string($con, $_POST['email']);
$pass = mysqli_real_escape_string($con, $_POST['pass']);

$sql = "UPDATE user SET upass = '".$pass."' WHERE uemail = '".$email."' ";

// Uncomment the following lines for debugging
// print($sql);
// die('Debugging');

if(mysqli_query($con, $sql)) {
    // Debugging: Comment out JSON responses and print messages for debugging
    // print('Password updated successfully');

    $response['code'] = 200;
    $response['status'] = 'Your password is updated!';
    echo json_encode($response);
} else {
    // Debugging: Comment out JSON responses and print error messages for debugging
    // print('Error updating password: ' . mysqli_error($con));

    $response['code'] = 400;
    $response['status'] = 'Something went wrong';
    echo json_encode($response);
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
<?php include("include/header.php");?>
</body>
</html>