<?php
// Check if PID is provided in the POST data
if(isset($_POST['pid'])) {
    // Retrieve PID from POST data
    $pid = $_POST['pid'];
    // Query to fetch property details based on the PID
    $query = mysqli_query($con, "SELECT property.*, user.uname, user.utype, user.uimage FROM property JOIN user ON property.uid=user.uid WHERE property.pid = '$pid'");
    $property = mysqli_fetch_array($query);

    // Display property details if found
    if($property) {
        // Output property details here
        echo '<div class="col-md-6">';
        echo '    <div class="featured-thumb hover-zoomer mb-4">';
        echo '        <div class="overlay-black overflow-hidden position-relative">';
        echo '            <img src="admin/property/'.$property['18'].'" alt="pimage">';
        echo '            <div class="sale bg-secondary text-white">For '.$property['5'].' </div>';
        echo '            <div class="price text-primary text-capitalize">&#8377;'.$property['13'].' <span class="text-white">'.$property['12'].' Sqft</span></div>';
        echo '        </div>';
        echo '        <div class="featured-thumb-data shadow-one">';
        echo '            <div class="p-4">';
        echo '                <h5 class="text-secondary hover-text-primary mb-2 text-capitalize"><a href="propertydetail.php?pid='.$property['0'].'">'.$property['1'].'</a></h5>';
        echo '                <span class="location text-capitalize"><i class="fas fa-map-marker-alt text-primary"></i> '.$property['14'].' '.$property['15'].'&nbsp;&nbsp;PID:'.$property['pid'].'</span>';
        echo '            </div>';
        echo '            <div class="px-4 pb-4 d-inline-block w-100">';
        echo '                <div class="float-left text-capitalize"><i class="fas fa-user text-primary mr-1"></i>By : '.$property['uname'].'</div>';
        echo '                <div class="float-right"><i class="far fa-calendar-alt text-primary mr-1"></i> '.$property['date'].'</div>';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    } else {
        echo '<p>Property not found!</p>';
    }
} else {
    echo '<p>Property ID not provided!</p>';
}
?>
