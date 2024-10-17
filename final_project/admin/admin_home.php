<?php
include 'connect_database.php';

// Query for count of deleted users
$query1 = "SELECT COUNT(*) AS count FROM register WHERE deleteval = 1";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($result1);
$totaluser = $row1['count'];

// Query for count of users with membership_id 2
$query2 = "SELECT COUNT(*) AS count FROM register WHERE membership_id = 2";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
$membership2Count = $row2['count'];

// Query for count of users with membership_id 3
$query3 = "SELECT COUNT(*) AS count FROM register WHERE membership_id = 3";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
$membership3Count = $row3['count'];

$query4 = "SELECT COUNT(*) AS count FROM games WHERE deleteval = 1";
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
$gameCount = $row4['count'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="path/to/bootstrap.css" />
    <!-- You can link Bootstrap or use custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/admin_home.css" />
    <!-- Custom CSS file for your styles -->
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php' ?>
    <!-- Admin Info Section (outside of navbar, left side) -->
    <div class="body-part">
        <div class="admin-info-container">
            <div id="admin-details" class="admin-details">
                <div class="admin-photo-container">
                    <img src="uploads/rick.jpg" alt="Admin Photo" class="admin-photo" />
                </div>
                <div class="admin-name">
                    <h3>Admin 123</h3>
                </div>
                <div class="admin-address">
                    <p><span class="label">Address:</span> National Plaza, S7, RC Dutt Rd, Aradhana Society, Vishwas
                        Colony, Alkapuri, Vadodara, Gujarat 390007</p>
                </div>
                <div class="admin-phone">
                    <p><span class="label">Phone:</span> +91 12345 6790</p>
                </div>
                <div class="admin-email">
                    <p><span class="label">Email:</span> admin@gmail.com</p>
                </div>
                <div class="admin-email">
                    <p><span class="label">GameZone:</span> GetInPlay</p>
                </div>
                <div class="admin-email">
                    <p><span class="label">open:</span> 10.00A.M </p>
                </div>
                <div class="admin-email">
                    <p><span class="label">close:</span> 11.30P.M</p>
                </div>
            </div>
        </div>
        <!-- Main Content Layout -->
        <div class="details">
            <div class="data">
                <div class="info">
                    <div class="info-content">
                        <h3 class="info-title"><i class="fa-solid fa-users"></i> Total Member</h3>
                        <p class="info-number count-value hidden"><?php echo $totaluser ?></p>
                    </div>
                </div>
                <div class="info">
                    <div class="info-content">
                        <h3 class="info-title"><i class="fa-solid fa-trophy"></i> Total game</h3>
                        <p class="info-number count-value hidden"><?php echo $gameCount ?> </p>
                    </div>
                </div>
                <div class="info">
                    <div class="info-content">
                        <h3 class="info-title"><i class="fa-solid fa-medal"></i> Silver</h3>
                        <p class="info-number count-value hidden"><?php echo $membership2Count ?></p>
                    </div>
                </div>
                <div class="info">
                    <div class="info-content">
                        <h3 class="info-title"><i class="fa-solid fa-award"></i> Gold</h3>
                        <p class="info-number count-value hidden"><?php echo $membership3Count ?></p>
                    </div>
                </div>

            </div>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Line Chart -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Book Slot Overview</h5>
                                <canvas id="myLineChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Bar Chart -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Trending Game</h5>
                                <canvas id="myBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Line Chart
                var ctxLine = document.getElementById('myLineChart').getContext('2d');
                var myLineChart = new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April'],
                        datasets: [{
                            label: 'Slot',
                            data: [10, 30, 25, 40],
                            borderColor: 'green',
                            backgroundColor: 'rgba(170, 228, 170, 0.2)',
                            borderWidth: 2,
                            fill: true
                        }]
                    }
                });

                // Bar Chart
                var ctxBar = document.getElementById('myBarChart').getContext('2d');
                var myBarChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: ['Bowling', 'Snooker', 'Chess', 'Pool'],
                        datasets: [{
                            label: 'Trending Game',
                            data: [20, 35, 15, 40],
                            borderColor: 'red',
                            backgroundColor: 'rgba(228, 170, 180, 0.2)',
                            borderWidth: 1
                        }]
                    }
                });

                document.addEventListener('DOMContentLoaded', function () {
                    const countElements = document.querySelectorAll('.count-value');

                    // Iterate over all count elements and trigger the animation
                    countElements.forEach((element) => {
                        // Simulate an animation to transition the numbers
                        setTimeout(() => {
                            element.classList.remove('hidden'); // Remove hidden class
                            element.classList.add('fade-in'); // Add fade-in class
                        }, 300); // Delay to make sure the page content is loaded before animation
                    });
                });
                // Function to animate number count
                // Function to animate number count
                function animateCount(element, endValue) {
                    let startValue = 0;
                    const duration = 2000; // 2 seconds duration
                    const stepTime = Math.max(Math.floor(duration / endValue), 50); // Prevent too fast updates

                    const interval = setInterval(function () {
                        startValue += 1;
                        element.textContent = `${startValue}`;
                        if (startValue >= endValue) {
                            clearInterval(interval);
                        }
                    }, stepTime);
                }

                // Run animation on all count-value elements
                document.addEventListener('DOMContentLoaded', function () {
                    const countElements = document.querySelectorAll('.count-value');

                    countElements.forEach((element) => {
                        const endValue = parseInt(element.textContent.trim(), 10);
                        element.textContent = '0'; // Reset the initial value
                        animateCount(element, endValue);
                    });
                });

            </script>



        </div>
    </div>
</body>

</html>