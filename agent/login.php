<?php
include '../components/connect.php';

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING); 
   $password = $_POST['pass'];
   $password = filter_var($password, FILTER_SANITIZE_STRING); 

   $select_agents = $conn->prepare("SELECT * FROM `agents` WHERE email = ? LIMIT 1");
   $select_agents->execute([$email]);
   $row = $select_agents->fetch(PDO::FETCH_ASSOC);

   if ($select_agents->rowCount() > 0 && password_verify($password, $row['password'])) {
      setcookie('agent_id', $row['id'], time() + 60*60*24*30, '/');
      header('location:dashboard.php');
   } else {
      $warning_msg[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="padding-left: 0;">
   
<!-- login section starts  -->
<section class="form-container" style="min-height: 100vh;">
   <form action="" method="post">
      <h3>welcome to agent login</h3>
      <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="Enter your password" class="box">
      <input type="submit" value="login now" name="submit" class="btn">
   </form>
</section>
<!-- login section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<?php include '../components/message.php'; ?>
</body>
</html>
