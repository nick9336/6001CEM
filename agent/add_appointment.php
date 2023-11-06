<?php  

include '../components/connect.php';

if(isset($_COOKIE['agent_id'])){
   $agent_id = $_COOKIE['agent_id'];
}else{
   $agent_id = '';
   header('location:login.php');
}

if (isset($_POST['add_appointment'])) {
    $property_id = $_POST['property_id'];
    $status = $_POST['status'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Validate and sanitize input data as needed.

    try {
        $insert_appointment = $conn->prepare("INSERT INTO appointments (agent_id, property_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?)");
        $insert_appointment->execute([$agent_id, $property_id, $appointment_date, $appointment_time, $status]);

        $message[] = 'Appointment added successfully!';
    } catch (PDOException $e) {
        $message[] = 'Error: ' . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_appointment = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $delete_appointment->execute([$delete_id]);
    header('location:add_appointment.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
    <?php include '../components/agent_header.php' ?>
    <section class="add-appointments">
        <form action="" method="POST">
            <h3>Add Appointment</h3>
            <select name="property_id" class="box" required>
                <option value="" disabled selected>Select Property</option>
                <?php
                $show_properties = $conn->prepare("SELECT id, property_title FROM property WHERE agent_id = ?");
                $show_properties->execute([$agent_id]);
                if ($show_properties->rowCount() > 0) {
                    while ($property = $show_properties->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $property['id'] . "'>" . $property['property_title'] . "</option>";
                    }
                }
                ?>
            </select>
            <input type="date" required name="appointment_date" class="box">
            <input type="time" required name="appointment_time" class="box">
            <select name="status" class="box" required>
                <option value="Scheduled">Scheduled</option>
                <option value="Completed">Completed</option>
                <option value="Canceled">Canceled</option>
            </select>
            <input type="submit" value="Add Appointment" name="add_appointment" class="btn">
        </form>
    </section>

    <section class="requests" style="padding-top: 0;">
        <div class="box-container">
            <?php
            $show_appointments = $conn->prepare("SELECT appointments.*, property.property_title 
                                                FROM appointments 
                                                INNER JOIN property ON appointments.property_id = property.id 
                                                WHERE appointments.agent_id = ?");
            $show_appointments->execute([$agent_id]);
            if ($show_appointments->rowCount() > 0) {
                while ($fetch_appointments = $show_appointments->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <div class="flex">
                            <p>Property: <?= $fetch_appointments['property_title']; ?> </p>
                            <p>Date: <?= $fetch_appointments['appointment_date']; ?> </p>
                            <p>Time: <?= $fetch_appointments['appointment_time']; ?></p>
                            <p>Status: <?= $fetch_appointments['status']; ?> </p>
                        </div>
                        <div class="flex-btn">
                            <a href="update_appointment.php?update=<?= $fetch_appointments['id']; ?>" class="option-btn">update</a>
                            <a href="add_appointment.php?delete=<?= $fetch_appointments['id']; ?>" class="delete-btn"
                                onclick="return confirm('Delete this appointment?');">delete</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No appointments added yet!</p>';
            }
            ?>
        </div>
    </section>
</body>
</html>
