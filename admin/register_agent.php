<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $agent_id = $_COOKIE['admin_id'];
}else{
   $agent_id = '';
   header('location:login.php');
}

// Initialize error messages array
$error_msg = array();
$success_msg = array();

if(isset($_POST['submit'])){
   // Sanitize and validate agent input
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
      $check_email = $conn->prepare("SELECT id FROM `agents` WHERE email = ?");
      $check_email->execute([$email]);
      if ($check_email->rowCount() > 0) {
         $error_msg[] = 'Email already exists.';
      }
   }

   if (empty($number) || !ctype_digit($number) || strlen($number) !== 10) {
      $error_msg[] = 'Please enter a valid 10-digit contact number.';
   } else {
      // Check if number already exists
      $check_number = $conn->prepare("SELECT id FROM `agents` WHERE number = ?");
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

      // Hash the password using sha1
      $c_pass = sha1($c_password);

      // Prepare and execute the SQL insert statement
      $insert_agent = $conn->prepare("INSERT INTO `agents` (name, number, email, password) VALUES (?, ?, ?, ?)");
      $insert_agent->execute([$name, $number, $email, $c_pass]);

   if ($insert_agent) {
    // Agent registration successful 

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'estatemy2023@gmail.com';
    $mail->Password = 'jwtbstjzqwjpeqlq';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('estatemy2023@gmail.com');
    $mail->addAddress($email); // Use the email entered during registration
    $mail->isHTML(true);
    $mail->Subject = 'Agent Registration Confirmation';
    $mail->Body = 'Dear ' . $name . ', <br/>
                   Your password is ' . $password . '<br/>
                   http://localhost/realestate/project/agent/login.php <br/><br/>
                   Click the link to login as an agent.';
    if ($mail->send()) {
        $success_msg[] = 'Agent registered. Confirmation email sent.';
    } else {
        $error_msg[] = 'Agent registered, but confirmation email could not be sent.';
    }

   } else {
    $error_msg[] = 'Agent registration failed. Please try again.';
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
   <link rel="stylesheet" href="../css/admin_style.css">
   

</head>
<body>
   
<?php include '../components/admin_header.php'; ?>

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post">
      <h3>create an agent account</h3>
      <input type="tel" name="name" required maxlength="50" placeholder="Enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="Enter your number" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="Enter your password" class="box">
      <input type="password" name="c_pass" required maxlength="20" placeholder="Confirm your password" class="box">
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<script src="../js/script.js"></script>


<?php include '../components/message.php'; ?>

</body>
</html>