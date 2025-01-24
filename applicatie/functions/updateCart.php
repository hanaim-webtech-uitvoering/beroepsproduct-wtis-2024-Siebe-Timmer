<?php
session_start();

function updateCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Verwijder een product
    if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['productId'])) {
        unset($_SESSION['cart'][$_GET['productId']]);
    }
    
    // Update alle hoeveelheden
    if (isset($_POST['action']) && $_POST['action'] === 'update_all' && isset($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $productId => $quantity) {
            if ((int)$quantity > 0) {
                $_SESSION['cart'][$productId] = (int)$quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }

    // Voeg een product toe of update hoeveelheid
    if (isset($_POST['action']) && $_POST['action'] === 'update' && 
        isset($_POST['productId']) && isset($_POST['quantity'])) {
        
        $quantity = (int)$_POST['quantity'];
        if ($quantity > 0) {
            $productId = $_POST['productId'];
            $_SESSION['cart'][$productId] = isset($_SESSION['cart'][$productId]) 
                ? $_SESSION['cart'][$productId] + $quantity 
                : $quantity;
        }
    }

    header('Location: ../cart.php');
    exit();
}

updateCart();
?>