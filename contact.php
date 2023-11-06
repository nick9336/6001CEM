<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['send'])){

   $msg_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_contact = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_contact->execute([$name, $email, $number, $message]);

   if($verify_contact->rowCount() > 0){
      $warning_msg[] = 'Message sent already!';
   }else{
      $send_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $send_message->execute([$msg_id, $name, $email, $number, $message]);
      $success_msg[] = 'Message send successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">
      <div class="image">
         <img src="images/contact.avif" height="600" alt="">
      </div>
      <form action="" method="post">
         <h3>get in touch</h3>
         <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
         <input type="number" name="number" required maxlength="10" max="9999999999" min="0" placeholder="Enter your number" class="box">
         <textarea name="message" placeholder="Type your message" required maxlength="1000" cols="30" rows="10" class="box"></textarea>
         <input type="submit" value="send message" name="send" class="btn">
      </form>
   </div>

</section>

<!-- contact section ends -->

<!-- faq section starts  -->

<section class="faq" id="faq">

   <h1 class="heading">FAQ</h1>

   <div class="box-container">

      <div class="box">
         <h3><span>How do I search for properties?</span><i class="fas fa-angle-down"></i></h3>
         <p>Properties can be serached on the home page or by using the 'Advanced Search' under the 'Tools' section.</p>
      </div>

      <div class="box">
         <h3><span>How can I provide feedback or suggest improvements?</span><i class="fas fa-angle-down"></i></h3>
         <p>Go to 'Support' -> 'Contact us' and fill in your feedback.</p>
      </div>

      <div class="box">
         <h3><span>How can property owners list their properties?</span><i class="fas fa-angle-down"></i></h3>
         <p>You just have to register an account on MyEstate to list a property on the website.</p>
      </div>

      <div class="box">
         <h3><span>How to contact with the buyers?</span><i class="fas fa-angle-down"></i></h3>
         <p>Simply click on the 'Send Enquiry' on the listing that you are interested and the agent/owner will get back to you during their working hours.</p>
      </div>

      <div class="box">
         <h3><span> Is there a mobile app available for download?</span><i class="fas fa-angle-down"></i></h3>
         <p>Apologies, our team is still further developing the mobile app.</p>
      </div>

      <div class="box">
         <h3><span>How do I create an account?</span><i class="fas fa-angle-down"></i></h3>
         <p>Simply click on 'Account' -> 'Register new' and fill all the necessary details.</p>
      </div>

   </div>

</section>

<!-- faq section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>