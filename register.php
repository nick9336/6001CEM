<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Initialize error messages array
$error_msg = array();

if(isset($_POST['submit'])){
   // Sanitize and validate user input
   $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
   $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   $number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_NUMBER_INT);
   $password = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
   $c_password = filter_input(INPUT_POST, 'c_pass', FILTER_SANITIZE_STRING);

   // Perform data validation
   if (empty($name)) {
      $error_msg[] = 'Please enter your name.';
   }

   if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_msg[] = 'Please enter a valid email address.';
   } else {
      // Check if email already exists
      $check_email = $conn->prepare("SELECT id FROM `users` WHERE email = ?");
      $check_email->execute([$email]);
      if ($check_email->rowCount() > 0) {
         $error_msg[] = 'Email already exists.';
      }
   }

   if (empty($number) || !ctype_digit($number) || strlen($number) !== 10) {
      $error_msg[] = 'Please enter a valid 10-digit contact number.';
   } else {
      // Check if number already exists
      $check_number = $conn->prepare("SELECT id FROM `users` WHERE number = ?");
      $check_number->execute([$number]);
      if ($check_number->rowCount() > 0) {
         $error_msg[] = 'Contact number already exists.';
      }
   }

   if (empty($password) || strlen($password) < 6) {
      $error_msg[] = 'Password must be at least 6 characters long.';
   }

   if ($password !== $c_password) {
      $error_msg[] = 'Passwords do not match.';
   }


   // If there are no errors, proceed with registration
   if (empty($error_msg)) {

      // Hash the password using password_hash
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare and execute the SQL insert statement
      $insert_user = $conn->prepare("INSERT INTO `users` (name, number, email, password) VALUES (?, ?, ?, ?)");
      $insert_user->execute([$name, $number, $email, $hashed_password]);

      if ($insert_user) {
         // User registration successful
         header('location: login.php');
     } else {
         $error_msg[] = 'Registration failed. Please try again.';
     }

      }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- register section starts  -->
<section class="form-container">
   <form action="" method="post">
      <h3>create a user account</h3>
      <input type="tel" name="name" required maxlength="50" placeholder="Enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="Enter your number" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="Enter your password" class="box">
      <input type="password" name="c_pass" required maxlength="20" placeholder="Confirm your password" class="box">
      <p>Already have an account? <a href="login.php">Login now</a></p>
      <input type="submit" value="register now" name="submit" class="btn">
   </form>
</section>
<!-- register section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
