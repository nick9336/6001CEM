<?php

include 'connect.php';

setcookie('agent_id', '', time() - 1, '/');

header('location:../agent/login.php');

?>