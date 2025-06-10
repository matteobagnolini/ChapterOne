<?php

require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update_account') {
    $user = $dbh->getCustomerByUsername( $_SESSION['username']); 
    $userId = $user['Id'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $submittedPassword = $_POST['password'] ?? ''; 
    $phone = $_POST['phone'] ?? '';

    $nameParts = explode(' ', trim($name), 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        $dbh->updateCustomer(
            $userId,
            $firstName,
            $lastName,
            $email,
            $submittedPassword, 
            $address,
            $phone
        );
}


header('Location: account.php');
exit;

?>