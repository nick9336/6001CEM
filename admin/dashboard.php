<?php


include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

$monthsProp = array();

for ($month = 1; $month <= 12; $month++) {
    $count = 0;
    
    $year = 2023;  // Replace with your desired year
    
    $select_properties = $conn->prepare("SELECT COUNT(*) AS property_count FROM `property` WHERE YEAR(date) = ? AND MONTH(date) = ?");
    $select_properties->execute([$year, $month]);
    
    $fetch_properties = $select_properties->fetch(PDO::FETCH_ASSOC);
    $count = $fetch_properties['property_count'];
    
    array_push($monthsProp, $count);
}

$monthsUsers = array();
$monthsAgents = array();

for ($month = 1; $month <= 12; $month++) {
    $userCount = 0;
    $agentCount = 0;

    $year = 2023;  // Replace with your desired year

    // Prepare and execute SQL queries to count users and agents for the specific month and year
    $selectUsers = $conn->prepare("SELECT COUNT(*) AS user_count FROM `users` WHERE YEAR(date) = ? AND MONTH(date) = ?");
    $selectAgents = $conn->prepare("SELECT COUNT(*) AS agent_count FROM `agents` WHERE YEAR(date) = ? AND MONTH(date) = ?");
    
    $selectUsers->execute([$year, $month]);
    $selectAgents->execute([$year, $month]);

    $fetchUsers = $selectUsers->fetch(PDO::FETCH_ASSOC);
    $fetchAgents = $selectAgents->fetch(PDO::FETCH_ASSOC);

    $userCount = $fetchUsers['user_count'];
    $agentCount = $fetchAgents['agent_count'];

    array_push($monthsUsers, $userCount);
    array_push($monthsAgents, $agentCount);
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
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

   <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update.php" class="btn">update profile</a>
   </div>

   <div class="box">
      <?php
         $select_listings = $conn->prepare("SELECT * FROM `property`");
         $select_listings->execute();
         $count_listings = $select_listings->rowCount();
      ?>
      <h3><?= $count_listings; ?></h3>
      <p>Property Posted</p>
      <a href="listings.php" class="btn">view listings</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $count_users = $select_users->rowCount();
      ?>
      <h3><?= $count_users; ?></h3>
      <p>Total Users</p>
      <a href="users.php" class="btn">view users</a>
   </div>

   <div class="box">
      <?php
         $select_agents = $conn->prepare("SELECT * FROM `agents`");
         $select_agents->execute();
         $count_agents = $select_agents->rowCount();
      ?>
      <h3><?= $count_agents; ?></h3>
      <p>Total Agents</p>
      <a href="agents.php" class="btn">view agents</a>
   </div>

   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $count_messages = $select_messages->rowCount();
      ?>
      <h3><?= $count_messages; ?></h3>
      <p>New Messages</p>
      <a href="messages.php" class="btn">view messages</a>
   </div>

   </div>

   <div class="box" style="padding:30px 0px 0px 0px;">
      
            <h1 class="heading">Graph - Monthly number of properties listed</h1>

            <canvas id="myChart"></canvas>

   </div>

   <div class="box" style="padding:30px 0px 0px 0px;">
      
            <h1 class="heading">Graph - Monthly number of registered users/agents</h1>

            <canvas id="myChart2"></canvas>

   </div>

</section>


<!-- dashboard section ends -->


















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<script>
   
      //graph 1
      var monthsProp = <?= json_encode($monthsProp); ?>;
      
      // Get the canvas element
      var ctx = document.getElementById('myChart').getContext('2d');

      // Define the chart data
      var data = {
         labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
         datasets: [{
            label: 'Property Count',
            data: monthsProp,
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

<script>
        var monthsUsers = <?= json_encode($monthsUsers); ?>;
        var monthsAgents = <?= json_encode($monthsAgents); ?>;


        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        var ctx = document.getElementById('myChart2').getContext('2d');
        var myChart2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Users',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        data: monthsUsers,
                    },
                    {
                        label: 'Agents',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: monthsAgents,
                    }
                ]
            },
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