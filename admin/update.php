<?php

include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    if (!empty($name)) {
        $verify_name = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
        $verify_name->execute([$name]);
        if ($verify_name->rowCount() > 0) {
            $warning_msg[] = 'Username already taken!';
        } else {
            $update_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
            $update_name->execute([$name, $admin_id]);
            $success_msg[] = 'Username updated!';
        }
    }

    $prev_pass = $fetch_profile['password'];
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $c_pass = $_POST['c_pass'];

    if (!empty($old_pass)) {
        if (password_verify($old_pass, $prev_pass)) {
            if ($c_pass != $new_pass) {
                $warning_msg[] = 'New password not matched!';
            } else {
                if (!empty($new_pass)) {
                    $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
                    $update_password = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
                    $update_password->execute([$hashed_password, $admin_id]);
                    $success_msg[] = 'Password updated!';
                } else {
                    $warning_msg[] = 'Please enter new password!';
                }
            }
        } else {
            $warning_msg[] = 'Old password not matched!';
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
    <title>Update</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <!-- header section starts  -->
    <?php include '../components/admin_header.php'; ?>
    <!-- header section ends -->

    <!-- update section starts  -->

    <section class="form-container">

        <form action="" method="POST">
            <h3>update profile</h3>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="old_pass" placeholder="enter old password" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="enter new password" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="c_pass" placeholder="confirm new password" maxlength="20" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="update now" name="submit" class="btn">
        </form>

    </section>

    <!-- update section ends -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>

    <?php include '../components/message.php'; ?>

</body>

</html>
