<?php
include 'components/connect.php';

// Check if the user is logged in
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';

// Initialize variables
$loan_amount = '';
$interest_rate = '';
$loan_term = '';
$down_payment = '';
$monthly_payment = '';

if (isset($_POST['calculate'])) {
    // Get user input
    $loan_amount = $_POST['loan'];
    $interest_rate = $_POST['interest'] / 100 / 12; // Monthly interest rate
    $loan_term = $_POST['term'] * 12; // Total number of payments (months)
    $down_payment = isset($_POST['down_payment']) ? $_POST['down_payment'] : 0;

    // Calculate monthly payment
    if ($loan_amount > 0 && $interest_rate > 0 && $loan_term > 0) {
        $monthly_payment = ($loan_amount - $down_payment) * ($interest_rate * pow(1 + $interest_rate, $loan_term)) / (pow(1 + $interest_rate, $loan_term) - 1);
    } else {
        $monthly_payment = 'Invalid input. Please check your values.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortgage Calculator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <section class="contact">
        <div class="row">
            <div class="image">
                <img src="images/mortgage.avif" alt="">
            </div>
            <form action="" method="post">
                <h3>Mortgage Calculator</h3>
                <input type="number" name="loan" required maxlength="10" placeholder="Loan amount" class="box">
                <input type="number" name="interest" required step="0.01" min="0" max="20" placeholder="Interest rate (%)" class="box">
                <input type="number" name="term" required maxlength="3" max="100" min="1" placeholder="Loan Term (years)" class="box">
                <input type="number" name="down_payment" required maxlength="10" placeholder="Down Payment" class="box">
                <input type="submit" value="Calculate" name="calculate" class="btn">

                <input type="text" name="result" placeholder="Result displayed" class="box" value="<?= isset($monthly_payment) ? number_format($monthly_payment, 2) : '' ?>">
            </form>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
    
    <?php include 'components/message.php'; ?>
</body>
</html>
