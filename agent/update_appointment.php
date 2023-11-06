<?php

include '../components/connect.php';

if(isset($_COOKIE['agent_id'])){
    $agent_id = $_COOKIE['agent_id'];
 }else{
    $agent_id = '';
    header('location:login.php');
 }

if (isset($_POST['update'])) {
    $appointment_id = $_POST['appointment_id'];
    $appointment_id = filter_var($appointment_id, FILTER_SANITIZE_STRING);
    $property_id = $_POST['property_id'];
    $property_id = filter_var($property_id, FILTER_SANITIZE_STRING);
    $appointment_date = $_POST['appointment_date'];
    $appointment_date = filter_var($appointment_date, FILTER_SANITIZE_STRING);
    $appointment_time = $_POST['appointment_time'];
    $appointment_time = filter_var($appointment_time, FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);

    $update_appointment = $conn->prepare("UPDATE `appointments` SET `property_id` = ?, `appointment_date` = ?, `appointment_time` = ?, `status` = ? WHERE `id` = ?");
    $update_appointment->execute([$property_id, $appointment_date, $appointment_time, $status, $appointment_id]);

    $message[] = 'Appointment updated!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <?php include '../components/agent_header.php' ?>

    <!-- update appointment section starts  -->

    <section class="update-appointment">

        <h1 class="heading">Update Appointment</h1>

        <?php
        $update_id = $_GET['update'];
        $show_appointments = $conn->prepare("SELECT * FROM `appointments` WHERE `id` = ?");
        $show_appointments->execute([$update_id]);
        if ($show_appointments->rowCount() > 0) {
            while ($fetch_appointments = $show_appointments->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="appointment_id" value="<?= $fetch_appointments['id']; ?>">
                    <span>Update Property</span>
                    <select name="property_id" class="box" required>
                        <option value="" disabled selected>Select Property</option>
                        <?php
                        $show_properties = $conn->prepare("SELECT id, property_title FROM property WHERE agent_id = ?");
                        $show_properties->execute([$agent_id]);
                        if ($show_properties->rowCount() > 0) {
                            while ($property = $show_properties->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($property['id'] == $fetch_appointments['property_id']) ? 'selected' : '';
                                echo "<option value='" . $property['id'] . "' $selected>" . $property['property_title'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span>Update Date</span>
                    <input type="date" required name="appointment_date" class="box" value="<?= $fetch_appointments['appointment_date']; ?>">
                    <span>Update Time</span>
                    <input type="time" required name="appointment_time" class="box" value="<?= $fetch_appointments['appointment_time']; ?>">
                    <span>Update Status</span>
                    <select name="status" class="box" required>
                        <option value="Scheduled" <?= ($fetch_appointments['status'] == 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                        <option value="Completed" <?= ($fetch_appointments['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="Canceled" <?= ($fetch_appointments['status'] == 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
                    </select>
                    <div class="flex-btn">
                        <input type="submit" value="Update" class="btn" name="update">
                        <a href="add_appointment.php" class="option-btn">Back</a>
                    </div>
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">No appointments added yet!</p>';
        }
        ?>

    </section>

    <!-- update appointment section ends -->

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>

    <script>
    <?php
    if (isset($_POST['update']) && !empty($message)) {
        echo 'window.location.href = "add_appointment.php";';
    }
    ?>
    </script>

</body>

</html>
