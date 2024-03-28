<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Booking Management System</title>
</head>
<body>
    <div class="col-md-12">
        <div class="form-group mt-4" id="buyButtonContainer">
            <!-- Button will be added here dynamically -->
        </div>
    </div>
</body>
<script>
    // JavaScript to add buy button based on user role
    window.onload = function() {
        // Make AJAX request to check user role
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.role == 'owner') {
                    // Create buy button
                    var buyButton = document.createElement('button');
                    buyButton.innerHTML = 'Buy Now';
                    buyButton.className = 'btn btn-primary w-100';
                    buyButton.onclick = function() {
                        // Add functionality for button click if needed
                    };

                    // Add button to container
                    var buyButtonContainer = document.getElementById('buyButtonContainer');
                    buyButtonContainer.appendChild(buyButton);
                }
            }
        };
        xhr.open('GET', 'check_user_role.php', true); // Adjust the URL accordingly
        xhr.send();
    };
</script>
</html>
