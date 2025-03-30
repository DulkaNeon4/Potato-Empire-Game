<?php
session_start();
include_once('storage.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cardIndex']) && isset($_POST['sellingPrice'])) {
        $cardIndex = $_POST['cardIndex'];
        $sellingPrice = $_POST['sellingPrice'];

        $username = $_SESSION['username'];
        $user = Storage::getUserByUsername($username);

        if (!$user) {
            // Redirect to login if user is not found
            header("Location: login.php");
            exit();
        }

        // Remove the sold card from the user's inventory
        $userCards = Storage::getUserCards($user);
        $key = array_search($cardIndex, $userCards);

        if ($key !== false) {
            unset($userCards[$key]);
            // Update user cards
            Storage::updateUserCards($username, $userCards);
        }

        // Calculate the amount to be added to the user's balance (90% of the selling price)
        $amountToAdd = round($sellingPrice * 0.9, 2); // Round to two decimal places

        // Update user's money
        $newBalance = round($user['money'] + $amountToAdd);
        Storage::updateUserMoney($username, $newBalance);

        $userPurchases = Storage::getUserPurchases();
        $key = array_search(['username' => $username, 'cardIndex' => $cardIndex], $userPurchases);

        if ($key !== false) {
            unset($userPurchases[$key]);
            // Update user purchases
            Storage::updateUserPurchases(array_values($userPurchases));
        }
    }
}

// Redirect back to user-details.php
header("Location: user-details.php");
exit();
?>
