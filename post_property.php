<?php  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

if(isset($_POST['post'])){

   $property_title = $_POST['property_title'];
   $property_title = filter_var($property_title, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $offer = $_POST['offer'];
   $offer = filter_var($offer, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);
   $furnished = $_POST['furnished'];
   $furnished = filter_var($furnished, FILTER_SANITIZE_STRING);
   $car_park = $_POST['car_park'];
   $car_park = filter_var($car_park, FILTER_SANITIZE_STRING);
   $bedroom = $_POST['bedroom'];
   $bedroom = filter_var($bedroom, FILTER_SANITIZE_STRING);
   $bathroom = $_POST['bathroom'];
   $bathroom = filter_var($bathroom, FILTER_SANITIZE_STRING);
   $balcony = $_POST['balcony'];
   $balcony = filter_var($balcony, FILTER_SANITIZE_STRING);
   $size = $_POST['size'];
   $size = filter_var($size, FILTER_SANITIZE_STRING); 
   $age = $_POST['age'];
   $age = filter_var($age, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   if(isset($_POST['lift'])){
      $lift = $_POST['lift'];
      $lift = filter_var($lift, FILTER_SANITIZE_STRING);
   }else{
      $lift = 'no';
   }
   if(isset($_POST['security_guard'])){
      $security_guard = $_POST['security_guard'];
      $security_guard = filter_var($security_guard, FILTER_SANITIZE_STRING);
   }else{
      $security_guard = 'no';
   }
   if(isset($_POST['play_ground'])){
      $play_ground = $_POST['play_ground'];
      $play_ground = filter_var($play_ground, FILTER_SANITIZE_STRING);
   }else{
      $play_ground = 'no';
   }
   if(isset($_POST['garden'])){
      $garden = $_POST['garden'];
      $garden = filter_var($garden, FILTER_SANITIZE_STRING);
   }else{
      $garden = 'no';
   }
   if(isset($_POST['gym'])){
      $gym = $_POST['gym'];
      $gym = filter_var($gym, FILTER_SANITIZE_STRING);
   }else{
      $gym = 'no';
   }
   if(isset($_POST['shopping_mall'])){
      $shopping_mall = $_POST['shopping_mall'];
      $shopping_mall = filter_var($shopping_mall, FILTER_SANITIZE_STRING);
   }else{
      $shopping_mall = 'no';
   }
   if(isset($_POST['hospital'])){
      $hospital = $_POST['hospital'];
      $hospital = filter_var($hospital, FILTER_SANITIZE_STRING);
   }else{
      $hospital = 'no';
   }
   if(isset($_POST['school'])){
      $school = $_POST['school'];
      $school = filter_var($school, FILTER_SANITIZE_STRING);
   }else{
      $school = 'no';
   }
   if(isset($_POST['market_area'])){
      $market_area = $_POST['market_area'];
      $market_area = filter_var($market_area, FILTER_SANITIZE_STRING);
   }else{
      $market_area = 'no';
   }
   if(isset($_POST['swimming_pool'])){
      $swimming_pool = $_POST['swimming_pool'];
      $swimming_pool = filter_var($swimming_pool, FILTER_SANITIZE_STRING);
   }else{
      $swimming_pool = 'no';
   }

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_02_ext = pathinfo($image_02, PATHINFO_EXTENSION);
   $rename_image_02 = create_unique_id().'.'.$image_02_ext;
   $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
   $image_02_size = $_FILES['image_02']['size'];
   $image_02_folder = 'uploaded_files/'.$rename_image_02;

   if(!empty($image_02)){
      if($image_02_size > 2000000){
         $warning_msg[] = 'image 02 size is too large!';
      }else{
         move_uploaded_file($image_02_tmp_name, $image_02_folder);
      }
   }else{
      $rename_image_02 = '';
   }

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_03_ext = pathinfo($image_03, PATHINFO_EXTENSION);
   $rename_image_03 = create_unique_id().'.'.$image_03_ext;
   $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
   $image_03_size = $_FILES['image_03']['size'];
   $image_03_folder = 'uploaded_files/'.$rename_image_03;

   if(!empty($image_03)){
      if($image_03_size > 2000000){
         $warning_msg[] = 'image 03 size is too large!';
      }else{
         move_uploaded_file($image_03_tmp_name, $image_03_folder);
      }
   }else{
      $rename_image_03 = '';
   }

   $image_04 = $_FILES['image_04']['name'];
   $image_04 = filter_var($image_04, FILTER_SANITIZE_STRING);
   $image_04_ext = pathinfo($image_04, PATHINFO_EXTENSION);
   $rename_image_04 = create_unique_id().'.'.$image_04_ext;
   $image_04_tmp_name = $_FILES['image_04']['tmp_name'];
   $image_04_size = $_FILES['image_04']['size'];
   $image_04_folder = 'uploaded_files/'.$rename_image_04;

   if(!empty($image_04)){
      if($image_04_size > 2000000){
         $warning_msg[] = 'image 04 size is too large!';
      }else{
         move_uploaded_file($image_04_tmp_name, $image_04_folder);
      }
   }else{
      $rename_image_04 = '';
   }

   $image_05 = $_FILES['image_05']['name'];
   $image_05 = filter_var($image_05, FILTER_SANITIZE_STRING);
   $image_05_ext = pathinfo($image_05, PATHINFO_EXTENSION);
   $rename_image_05 = create_unique_id().'.'.$image_05_ext;
   $image_05_tmp_name = $_FILES['image_05']['tmp_name'];
   $image_05_size = $_FILES['image_05']['size'];
   $image_05_folder = 'uploaded_files/'.$rename_image_05;

   if(!empty($image_05)){
      if($image_05_size > 2000000){
         $warning_msg[] = 'image 05 size is too large!';
      }else{
         move_uploaded_file($image_05_tmp_name, $image_05_folder);
      }
   }else{
      $rename_image_05 = '';
   }

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_01_ext = pathinfo($image_01, PATHINFO_EXTENSION);
   $rename_image_01 = create_unique_id().'.'.$image_01_ext;
   $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
   $image_01_size = $_FILES['image_01']['size'];
   $image_01_folder = 'uploaded_files/'.$rename_image_01;

   if($image_01_size > 2000000){
      $warning_msg[] = 'image 01 size too large!';
   }else{
      $insert_property = $conn->prepare("INSERT INTO `property`(user_id, property_title, address, price, type, offer, furnished, car_park, bedroom, bathroom, balcony, size, age, swimming_pool, lift, security_guard, play_ground, garden, gym, shopping_mall, hospital, school, market_area, image_01, image_02, image_03, image_04, image_05, description) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"); 
      $insert_property->execute([$user_id, $property_title, $address, $price, $type, $offer, $furnished, $car_park, $bedroom, $bathroom, $balcony, $size, $age, $swimming_pool, $lift, $security_guard, $play_ground, $garden, $gym, $shopping_mall, $hospital, $school, $market_area, $rename_image_01, $rename_image_02, $rename_image_03, $rename_image_04, $rename_image_05, $description]);
      move_uploaded_file($image_01_tmp_name, $image_01_folder);

      
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
      $select_user->execute([$user_id]);
      $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);


         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = 'smtp.gmail.com';
         $mail->SMTPAuth = true;
         $mail->Username = 'estatemy2023@gmail.com';
         $mail->Password = 'jwtbstjzqwjpeqlq';
         $mail->SMTPSecure = 'ssl';
         $mail->Port = 465;
         $mail->setFrom('estatemy2023@gmail.com');
         $mail->addAddress($fetch_user['email']); // Use the email entered during registration
         $mail->isHTML(true);
         $mail->Subject = 'Property Listing Confirmation';
         $mail->Body = 'Dear ' . $fetch_user['name'] . ', <br/>
                     You have successfully posted a listing as a property owner. <br/>
                     http://localhost/realestate/project/my_listings.php <br/><br/>
                     Click the link to view your listings.';
            
      if ($mail->send()) {
        $success_msg[] = 'Property posted.';
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
   <title>post property</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="property-form">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>property details</h3>
      <div class="box">
         <p>Property Name <span>*</span></p>
         <input type="text" name="property_title" required maxlength="50" placeholder="Enter property name" class="input">
      </div>
      <div class="flex">
         <div class="box">
            <p>Property Price <span>*</span></p>
            <input type="number" name="price" required min="0" max="9999999999" maxlength="10" placeholder="Enter property price" class="input">
         </div>
         <div class="box">
            <p>Property Address <span>*</span></p>
            <input type="text" name="address" required maxlength="100" placeholder="Enter property full address" class="input">
         </div>
         <div class="box">
            <p>Offer Type <span>*</span></p>
            <select name="offer" required class="input">
               <option value="Sale">Sale</option>
               <option value="Rent">Rent</option>
            </select>
         </div>
         <div class="box">
            <p>Property Type <span>*</span></p>
            <select name="type" required class="input">
                  <option value="Flat">Flat</option>
                  <option value="Condominium">Condominium</option>
                  <option value="Semi-D">Semi-D</option>
                  <option value="Bungalow">Bungalow</option>
                  <option value="Terrace">Terrace</option>
            </select>
         </div>
         <div class="box">
            <p>Furnished Status <span>*</span></p>
            <select name="furnished" required class="input">
               <option value="Furnished">Furnished</option>
               <option value="Unfurnished">Unfurnished</option>
            </select>
         </div>
         <div class="box">
            <p>Car Parks <span>*</span></p>
            <input type="number" name="car_park" required min="0" max="9" maxlength="1" placeholder="Enter number of car parks" class="input">
         </div>
         <div class="box">
            <p>Bedrooms <span>*</span></p>
            <input type="number" name="bedroom" required min="1" max="20" maxlength="1" placeholder="Enter number of bedrooms" class="input">
         </div>
         <div class="box">
            <p>Bathrooms <span>*</span></p>
            <input type="number" name="bathroom" required min="0" max="9" maxlength="1" placeholder="Enter number of bathrooms" class="input">
         </div>
         <div class="box">
            <p>Balconys <span>*</span></p>
            <input type="number" name="balcony" required min="0" max="9" maxlength="1" placeholder="Enter number of balconys" class="input">
         </div>
         <div class="box">
            <p>Built-up Size <span>*</span></p>
            <input type="number" name="size" required min="500" max="9999999999" maxlength="10" placeholder="Squarefeet" class="input">
         </div>
         <div class="box">
            <p>Property Age <span>*</span></p>
            <input type="number" name="age" required min="0" max="99" maxlength="2" placeholder="How old is the property?" class="input">
         </div>
      </div>
      <div class="box">
         <p>Property Description <span>*</span></p>
         <textarea name="description" maxlength="1000" class="input" required cols="30" rows="10" placeholder="Write about property..."></textarea>
      </div>
      <div class="checkbox">
         <div class="box">
            <p><input type="checkbox" name="lift" value="yes" />Lifts</p>
            <p><input type="checkbox" name="security_guard" value="yes" />Guarded</p>
            <p><input type="checkbox" name="play_ground" value="yes" />Play Ground</p>
            <p><input type="checkbox" name="garden" value="yes" />Garden</p>
            <p><input type="checkbox" name="swimming_pool" value="yes" />Swimming Pool</p>

         </div>
         <div class="box">
            <p><input type="checkbox" name="gym" value="yes" />Gym</p>
            <p><input type="checkbox" name="shopping_mall" value="yes" />Shopping Mall</p>
            <p><input type="checkbox" name="hospital" value="yes" />Hospital</p>
            <p><input type="checkbox" name="school" value="yes" />School</p>
            <p><input type="checkbox" name="market_area" value="yes" />Market Area</p>
         </div>
      </div>
      <div class="box">
         <p>image 01 <span>*</span></p>
         <input type="file" name="image_01" class="input" accept="image/*" required>
      </div>
      <div class="flex"> 
         <div class="box">
            <p>image 02</p>
            <input type="file" name="image_02" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>image 03</p>
            <input type="file" name="image_03" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>image 04</p>
            <input type="file" name="image_04" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>image 05</p>
            <input type="file" name="image_05" class="input" accept="image/*">
         </div>   
      </div>
      <input type="submit" value="post property" class="btn" name="post">
   </form>

</section>





<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>