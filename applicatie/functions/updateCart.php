<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['productId']) ? (int)$_POST['productId'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $action = $_POST['action'] ?? '';

    if ($productId > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        switch ($action) {
            case 'update':
                if ($quantity > 0) {
                    if (isset($_SESSION['cart'][$productId])) {
                        $_SESSION['cart'][$productId] += $quantity;
                    } else {
                        $_SESSION['cart'][$productId] = $quantity;
                    }
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
                break;

            case 'removeOne':
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]--;
                    if ($_SESSION['cart'][$productId] <= 0) {
                        unset($_SESSION['cart'][$productId]);
                    }
                }
                break;

            case 'remove':
                unset($_SESSION['cart'][$productId]);
                break;

            case 'set':
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId] = $quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
                break;

            default:
                break;
        }
    }
}
$redirectUrl = $_SERVER['HTTP_REFERER'] ?? '../cart.php';
header('Location: ' . $redirectUrl);
exit();
