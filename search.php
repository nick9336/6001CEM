<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

include 'components/save_send.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- search filter section starts  -->

<section class="filters" style="padding-bottom: 0;">

   <form action="" method="post">
      <div id="close-filter"><i class="fas fa-times"></i></div>
      <h3>filter your search</h3>
         
         <div class="flex">
            <div class="box">
               <p>Enter Location**</p>
               <input type="text" name="location" required maxlength="50" placeholder="Enter location, city or neighbourhood" class="input">
            </div>
            <div class="box">
               <p>Offer Type</p>
               <select name="offer" class="input">
                  <option value="">Select Offer Type</option>
                  <option value="Sale">Sale</option>
                  <option value="Rent">Rent</option>
               </select>
            </div>
            <div class="box">
               <p>Property Type</p>
               <select name="type" class="input">
                  <option value="">Select Property Type</option>
                  <option value="Flat">Flat</option>
                  <option value="Condominium">Condominium</option>
                  <option value="Semi-D">Semi-D</option>
                  <option value="Bungalow">Bungalow</option>
                  <option value="Terrace">Terrace</option>
               </select>
            </div>
            <div class="box">
               <p>Bedrooms</p>
               <input type="number" name="bedroom" maxlength="1" placeholder="Enter number of bedrooms" class="input">
            </div>
            <div class="box">
               <p>Minimum Budget</p>
               <input type="number" name="min" maxlength="999999" placeholder="RM" class="input">
            </div>
            <div class="box">
               <p>Maximum Budget</p>
               <input type="number" name="max" maxlength="999999" placeholder="RM" class="input">
            </div>
            <div class="box">
               <p>Furnished Status</p>
               <select name="furnished" class="input">
                  <option value="">Select Furnishing</option>
                  <option value="Unfurnished">Unfurnished</option>
                  <option value="Furnished">Furnished</option>
               </select>
            </div>
         </div>
         <input type="submit" value="search property" name="filter_search" class="btn">
   </form>

</section>

<!-- search filter section ends -->

<div id="filter-btn" class="fas fa-filter"></div>

<?php

$whereClause = '';

if (isset($_POST['h_search'])) {
   // Check if each filter is set and not empty, then add it to the filters array
   if (!empty($_POST['h_location'])) {
       $filters[] = "address LIKE '%" . $_POST['h_location'] . "%'";
   }
   if (!empty($_POST['h_type'])) {
       $filters[] = "type LIKE '%" . $_POST['h_type'] . "%'";
   }
   if (!empty($_POST['h_offer'])) {
       $filters[] = "offer LIKE '%" . $_POST['h_offer'] . "%'";
   }
   if (!empty($_POST['h_min']) && !empty($_POST['h_max'])) {
       $filters[] = "price BETWEEN " . $_POST['h_min'] . " AND " . $_POST['h_max'];
   }

   // Construct the WHERE clause based on the filters
   if (!empty($filters)) {
       $whereClause = "WHERE " . implode(" AND ", $filters);
   }

}elseif (isset($_POST['filter_search'])) {
    // Check if each filter is set and not empty, then add it to the filters array
    if (!empty($_POST['location'])) {
        $filters[] = "address LIKE '%" . $_POST['location'] . "%'";
    }
    if (!empty($_POST['type'])) {
        $filters[] = "type LIKE '%" . $_POST['type'] . "%'";
    }
    if (!empty($_POST['offer'])) {
        $filters[] = "offer LIKE '%" . $_POST['offer'] . "%'";
    }
    if (!empty($_POST['bedroom'])) {
        $filters[] = "bedroom = " . $_POST['bedroom'];
    }
    if (!empty($_POST['min']) && !empty($_POST['max'])) {
        $filters[] = "price BETWEEN " . $_POST['min'] . " AND " . $_POST['max'];
    }
    if (!empty($_POST['furnished'])) {
        $filters[] = "furnished LIKE '%" . $_POST['furnished'] . "%'";
    }

    // Construct the WHERE clause based on the filters
    if (!empty($filters)) {
        $whereClause = "WHERE " . implode(" AND ", $filters);
    }
}

// Modify the SQL query to include the WHERE clause
$select_properties = $conn->prepare("SELECT * FROM `property` " . $whereClause . " ORDER BY date DESC");
$select_properties->execute();

?>

<!-- listings section starts  -->

<section class="listings">

   <?php 
      if(isset($_POST['h_search']) or isset($_POST['filter_search'])){
         echo '<h1 class="heading">search results</h1>';
      }else{
         echo '<h1 class="heading">latest listings</h1>';
      }
   ?>

   <div class="box-container">
      <?php
         $total_images = 0;
         if($select_properties->rowCount() > 0){
            while($fetch_property = $select_properties->fetch(PDO::FETCH_ASSOC)){
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_user->execute([$fetch_property['user_id']]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

            $select_agent = $conn->prepare("SELECT * FROM `agents` WHERE id = ?");
            $select_agent->execute([$fetch_property['agent_id']]);
            $fetch_agent = $select_agent->fetch(PDO::FETCH_ASSOC);

            $isOwner = ($user_id === $fetch_property['user_id']);
            $isAgent = ($fetch_agent !== false); 

            if(!empty($fetch_property['image_02'])){
               $image_coutn_02 = 1;
            }else{
               $image_coutn_02 = 0;
            }
            if(!empty($fetch_property['image_03'])){
               $image_coutn_03 = 1;
            }else{
               $image_coutn_03 = 0;
            }
            if(!empty($fetch_property['image_04'])){
               $image_coutn_04 = 1;
            }else{
               $image_coutn_04 = 0;
            }
            if(!empty($fetch_property['image_05'])){
               $image_coutn_05 = 1;
            }else{
               $image_coutn_05 = 0;
            }

            $total_images = (1 + $image_coutn_02 + $image_coutn_03 + $image_coutn_04 + $image_coutn_05);

            $select_saved = $conn->prepare("SELECT * FROM `saved` WHERE property_id = ? and user_id = ?");
            $select_saved->execute([$fetch_property['id'], $user_id]);

      ?>
      <form action="" method="POST">
         <div class="box">
            <input type="hidden" name="property_id" value="<?= $fetch_property['id']; ?>">
            <?php
               if($select_saved->rowCount() > 0){
            ?>
            <button type="submit" name="save" class="save"><i class="fas fa-heart"></i><span>saved</span></button>
            <?php
               }else{ 
            ?>
            <button type="submit" name="save" class="save"><i class="far fa-heart"></i><span>save</span></button>
            <?php
               }
            ?>
            <div class="thumb">
               <p class="total-images"><i class="far fa-image"></i><span><?= $total_images; ?></span></p> 
               <img src="uploaded_files/<?= $fetch_property['image_01']; ?>" alt="">
            </div>
            <div class="admin">
            <h2>
               <?php
                  if ($isAgent) {
                     // Display "Agent" if the user is an agent
                     echo 'Estate <br>';
                     echo 'Agent';
                  } else {
                     // Display "Property Owner" if the user is not an agent
                     echo 'Property <br>';
                     echo 'Owner';
                  }
               ?>
            </h2>
               <div>
                  <p>
                  <?php
                     if ($isAgent) {
                         // Display the agent's name if the user is an agent
                           echo $fetch_agent['name'];
                     } else {
                        // Display the property owner's name if the user is not an agent
                           echo $fetch_user['name'];
                     }
                  ?>
                  </p>
                  <span><?= date('F j, Y', strtotime($fetch_property['date'])); ?></span>
               </div>
            </div>
         </div>
         <div class="box">
            <div class="price"><span>RM <?= $fetch_property['price']; ?></span></div>
            <h3 class="name"><?= $fetch_property['property_title']; ?></h3>
            <p class="location"><i class="fas fa-map-marker-alt"></i><span><?= $fetch_property['address']; ?></span></p>
            <div class="flex">
               <p><i class="fas fa-house"></i><span><?= $fetch_property['type']; ?></span></p>
               <p><i class="fas fa-tag"></i><span><?= $fetch_property['offer']; ?></span></p>
               <p><i class="fas fa-bed"></i><span><?= $fetch_property['bedroom']; ?> Bedroom</span></p>
               <p><i class="fas fa-couch"></i><span><?= $fetch_property['furnished']; ?></span></p>
               <p><i class="fas fa-maximize"></i><span><?= $fetch_property['size']; ?> sqft</span></p>
            </div>
            <div class="flex-btn">
               <a href="view_property.php?get_id=<?= $fetch_property['id']; ?>" class="btn">view property</a>
               <input type="submit" value="send enquiry" name="send" class="btn">
            </div>
         </div>
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">No results found!</p>';
      }
      ?>
      
   </div>

</section>

<!-- listings section ends -->











<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<script>

document.querySelector('#filter-btn').onclick = () =>{
   document.querySelector('.filters').classList.add('active');
}

document.querySelector('#close-filter').onclick = () =>{
   document.querySelector('.filters').classList.remove('active');
}

</script>

</body>
</html>