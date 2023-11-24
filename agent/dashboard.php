<?php

include '../components/connect.php';

if(isset($_COOKIE['agent_id'])){
   $agent_id = $_COOKIE['agent_id'];
}else{
   $agent_id = '';
   header('location:login.php');
}

$daysReq = array();

// Assuming you want to count for a specific month and year
$year = 2023;
$month = 11; // Replace with your desired month

// Get the number of days in the specified month and year
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

for ($day = 1; $day <= $daysInMonth; $day++) {
    $count = 0;
    
    // Assuming 'date' is the column in your 'requests' table where the date is stored
    $select_requests = $conn->prepare("SELECT COUNT(*) AS request_count FROM `requests` WHERE receiver = ? AND YEAR(date) = ? AND MONTH(date) = ? AND DAY(date) = ?");
    $select_requests->execute([$agent_id, $year, $month, $day]);
    
    $fetch_requests = $select_requests->fetch(PDO::FETCH_ASSOC);
    $count = $fetch_requests['request_count'];
    
    array_push($daysReq, $count);
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/agent_header.php'; ?>
<!-- header section ends -->

<!-- dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

   <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `agents` WHERE id = ? LIMIT 1");
         $select_profile->execute([$agent_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update.php" class="btn">update profile</a>
   </div>

   <div class="box">
   <?php
        $count_properties = $conn->prepare("SELECT * FROM `property` WHERE agent_id = ?");
        $count_properties->execute([$agent_id]);
        $total_properties = $count_properties->rowCount();
      ?>
      <h3><?= $total_properties; ?></h3>
      <p>Properties Listed</p>
      <a href="my_listings.php" class="btn">view all listings</a>
   </div>

   <div class="box">
      <?php
        $count_requests_received = $conn->prepare("SELECT * FROM `requests` WHERE receiver = ?");
        $count_requests_received->execute([$agent_id]);
        $total_requests_received = $count_requests_received->rowCount();
      ?>
      <h3><?= $total_requests_received; ?></h3>
      <p>Requests Received</p>
      <a href="requests_received.php" class="btn">view requests received</a>
   </div>

   <div class="box">
   <?php
        $count_appointments = $conn->prepare("SELECT * FROM `appointments` WHERE agent_id = ?");
        $count_appointments->execute([$agent_id]);
        $total_appointments = $count_appointments->rowCount();
      ?>
      <h3><?= $total_appointments; ?></h3>
      <p>Appointments</p>
      <a href="add_appointment.php" class="btn">view all appointments</a>
   </div>

   </div>

   <div class="box" style="padding:30px 0px 0px 0px;">
      
         <div class="box-conatiner">
            <h1 class="heading">Graph - Daily number of requests</h1>

            <canvas id="myChart"></canvas>
         </div>

      </div>

</section>


<!-- dashboard section ends -->




















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<script>
    // Retrieve the daily request counts from PHP
    var daysReq = <?= json_encode($daysReq); ?>;
    
    // Get the canvas element
    var ctx = document.getElementById('myChart').getContext('2d');

    // Define the chart data
    var data = {
        labels: Array.from({ length: <?= $daysInMonth ?> }, (_, i) => (i + 1).toString()), // Generates an array of day numbers
        datasets: [{
            label: 'Request Count',
            data: daysReq,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    // Create the chart
    var myChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>



<?php include '../components/message.php'; ?>

</body>
</html>