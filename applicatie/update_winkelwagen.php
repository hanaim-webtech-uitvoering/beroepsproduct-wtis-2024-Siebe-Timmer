<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

function updateWinkelwagen($action, $productId, $quantity = 0) {
    if (!$productId) {
        return false;
    }

    switch ($action) {
        case 'update':
            if ($quantity > 0) {
                $_SESSION['winkelwagen'][$productId] = $quantity;
            }
            return true;
            
        case 'remove':
            unset($_SESSION['winkelwagen'][$productId]);
            return true;
            
        default:
            return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = $_POST['productId'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 0);
    
    if (updateWinkelwagen($action, $productId, $quantity)) {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'menu.php';
        header("Location: $referer");
        exit;
    } else {
        $_SESSION['error'] = "Er ging iets mis bij het bijwerken van de winkelwagen.";
        header('Location: menu.php');
        exit;
    }
}