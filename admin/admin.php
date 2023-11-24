<?php
include '../components/connect.php';

$password = 'Admin123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$updatePassword = $conn->prepare("UPDATE `admins` SET password = ? WHERE name = 'admin'");
$updatePassword->execute([$hashedPassword]);

echo 'Password updated successfully!';
?>

