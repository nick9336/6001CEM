<?php

if(isset($_POST['save'])){
   if($user_id != ''){

      $save_id = create_unique_id();
      $property_id = $_POST['property_id'];
      $property_id = filter_var($property_id, FILTER_SANITIZE_STRING);

      $verify_saved = $conn->prepare("SELECT * FROM `saved` WHERE property_id = ? and user_id = ?");
      $verify_saved->execute([$property_id, $user_id]);

      if($verify_saved->rowCount() > 0){
         $remove_saved = $conn->prepare("DELETE FROM `saved` WHERE property_id = ? AND user_id = ?");
         $remove_saved->execute([$property_id, $user_id]);
         $success_msg[] = 'Removed from saved!';
      }else{
         $insert_saved = $conn->prepare("INSERT INTO`saved`(id, property_id, user_id) VALUES(?,?,?)");
         $insert_saved->execute([$save_id, $property_id, $user_id]);
         $success_msg[] = 'Listing saved!';
      }

   }else{
      $warning_msg[] = 'Please login first!';
   }
}

if (isset($_POST['send'])) {
   if ($user_id != '') {
       $request_id = create_unique_id();
       $property_id = $_POST['property_id'];
       $property_id = filter_var($property_id, FILTER_SANITIZE_STRING);

       // Retrieve user and agent IDs from the property table
       $select_property = $conn->prepare("SELECT user_id, agent_id FROM `property` WHERE id = ?");
       $select_property->execute([$property_id]);
       $fetch_property = $select_property->fetch(PDO::FETCH_ASSOC);

       // Set the receiver based on property ownership
       $receiver = ($user_id == $fetch_property['user_id']) ? $fetch_property['agent_id'] : $fetch_property['user_id'];

       // Check if the request is already sent
       $verify_request = $conn->prepare("SELECT * FROM `requests` WHERE property_id = ? AND sender = ? AND receiver = ?");
       $verify_request->execute([$property_id, $user_id, $receiver]);

       if ($verify_request->rowCount() > 0) {
           $warning_msg[] = 'Request sent already!';
       } else {
           // Send request
           $send_request = $conn->prepare("INSERT INTO `requests`(id, property_id, sender, receiver) VALUES(?,?,?,?)");
           $send_request->execute([$request_id, $property_id, $user_id, $receiver]);

           $success_msg[] = 'Request sent successfully!';
       }
   } else {
       $warning_msg[] = 'Please login first!';
   }
}



?>