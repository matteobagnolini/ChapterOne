<?php
require_once 'bootstrap.php';


if (!isUserLoggedIn()) {
    header("location: login.php");
    exit;
}

if (!isset($_POST['cart_id'])) {
    header("location: cart.php");
    exit;
}


$cartId = $_POST['cart_id'];
$discountCode = isset($_POST['discount_code']) && !empty($_POST['discount_code']) ? $_POST['discount_code'] : null;

try {

    $customerId =  $dbh->getAccountInfo($_SESSION["username"])['Id'];
   
    $discountCodeId = null;
    if ($discountCode) {

        $discountCodes = $dbh->getDiscountCodes();
        foreach ($discountCodes as $code) {
            if ($code['Code'] === $discountCode) {

                $currentDate = date('Y-m-d');
                if ($code['Active'] && $currentDate >= $code['Start_date'] && $currentDate <= $code['End_date']) {
                    $discountCodeId = $code['Id'];
                    break;
                }
            }
        }
        

        if (!$discountCodeId) {
            $_SESSION['error'] = "Codice sconto non valido o scaduto.";
            header("location: cart.php");
            exit;
        }
    }
    

    $date = date('Y-m-d H:i:s');
    
    $cart = $dbh->getCartByCustomerId($customerId);
    $total = $cart['Subtotal'];
    

    $orderId = $dbh->insertOrder($date, $total, $customerId, $discountCodeId);
    
    if ($orderId) {
        $_SESSION['success'] = "Ordine creato con successo! Il tuo ordine è in elaborazione.";
        header("location:  cart.php");
    } else {
        $_SESSION['error'] = "Errore durante la creazione dell'ordine. Riprova più tardi.";
        header("location: cart.php");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = "Si è verificato un errore: " . $e->getMessage();
    header("location: cart.php");
}
?>