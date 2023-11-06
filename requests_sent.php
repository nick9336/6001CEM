<?php  
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['request_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `requests` WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_request = $conn->prepare("DELETE FROM `requests` WHERE id = ?");
        $delete_request->execute([$delete_id]);
        $success_msg[] = 'Request deleted successfully!';
    } else {
        $warning_msg[] = 'Request deleted already!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Requests</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="requests">
    <h1 class="heading">Sent Requests</h1>
    <div class="box-container">

    <?php
    $select_requests = $conn->prepare("SELECT * FROM `requests` WHERE sender = ?");
    $select_requests->execute([$user_id]);

    if ($select_requests->rowCount() > 0) {
        while ($fetch_request = $select_requests->fetch(PDO::FETCH_ASSOC)) {
            // Determine whether the receiver is a user or agent

            $select_property = $conn->prepare("SELECT * FROM `property` WHERE id = ?");
            $select_property->execute([$fetch_request['property_id']]);
            $fetch_property = $select_property->fetch(PDO::FETCH_ASSOC);

            $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_user->execute([$fetch_property['user_id']]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
   
            $select_agent = $conn->prepare("SELECT * FROM `agents` WHERE id = ?");
            $select_agent->execute([$fetch_property['agent_id']]);
            $fetch_agent = $select_agent->fetch(PDO::FETCH_ASSOC);
   
    ?>
        <div class="box">
             <?php
               if (!empty($fetch_user)) {
                  echo '<p>Name: </i>' . $fetch_user['name'] . '</p>';
                  echo '<p>Number: </i><a href="tel:' . $fetch_user['number'] . '">' . $fetch_user['number'] . '</a></p>';
                  echo '<p>Email: </i><a href="mailto:' . $fetch_user['email'] . '">' . $fetch_user['email'] . '</a></p>';
               }
            
               if (!empty($fetch_agent)) {
                  echo '<p>Name: </i><span>' . $fetch_agent['name'] . '</span></p>';
                  echo '<p>Number: </i><a href="tel:' . $fetch_agent['number'] . '">' . $fetch_agent['number'] . '</a></p>';
                  echo '<p>Email: </i><a href="mailto:' . $fetch_agent['email'] . '">' . $fetch_agent['email'] . '</a></p>';

               }
             ?>
            <p>Enquiry for: <span><?= $fetch_property['property_title']; ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="request_id" value="<?= $fetch_request['id']; ?>">
                <input type="submit" value="Delete Request" class="btn" onclick="return confirm('Remove this request?');" name="delete">
                <a href="view_property.php?get_id=<?= $fetch_property['id']; ?>" class="btn">View Property</a>
            </form>
        </div>
    <?php
        }
    } else {
        echo '<p class="empty">You have no sent requests!</p>';
    }
    ?>

    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
