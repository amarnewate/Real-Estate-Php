<?php
require 'vendor/autoload.php';
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

// Initialize PhoneNumberUtil
$phoneUtil = PhoneNumberUtil::getInstance();

// Get the posted phone number and country code
$phone = $_POST['phone'];
$country = $_POST['country'];
$number = $country . $phone;

// Check if the input phone number meets a minimum length requirement
if(strlen($phone) < 3 || strlen($country) < 1){
    echo "<span class='text-danger'>Phone number is too short</span>";
    exit; // Stop further execution
}

// Parse the phone number
$numberProto = $phoneUtil->parse($number, $country);

// Validate the phone number
$isValid = $phoneUtil->isValidNumber($numberProto);

// Return response based on validation result
if($isValid){
    // Format the phone number in international format
    $formattedNumber = $phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL);
    echo "<span class='text-success'>Phone number ($formattedNumber) is valid</span>";

} elseif ($phoneUtil->isPossibleNumber($numberProto)) {
    // If the number is possible but not valid, echo appropriate message
    echo "<span class='text-warning'>Phone number is possible but not valid</span>";
} else {
    // If the number is not possible, echo error message
    echo "<span class='text-danger'>Phone number is not valid</span>";
}
?>
