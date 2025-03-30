<?php
session_start();
include_once('storage.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        // Get user details
        $username = $_SESSION['username'];
        $user = Storage::getUserByUsername($username);

        if ($user) {
            // Get card index from the form
            $cardIndex = $_POST['index'];

            // Get card details
            $cards = Storage::getCards();
            $card = $cards[$cardIndex];

            // Check if the user has reached the card limit
            if (count($user['cards']) >= 5) {
                // Display an alert message for exceeding the card limit
                echo '<script>alert("You can only buy 5 cards!"); window.location.href = "main.php";</script>';
                exit();
            }

            // Check if the user has enough balance to buy the card
            if ($user['money'] >= $card['price']) {
                // Deduct the card price from the user's balance
                $user['money'] -= $card['price'];

                // Add the card to the user's cards
                $user['cards'][] = $cardIndex;

                // Update user and card data
                Storage::updateUser($user);
                Storage::updateCard($cardIndex, $card);

                // Add purchase record
                $purchases = Storage::getUserPurchases();
                $purchases[] = ['username' => $user['username'], 'cardIndex' => $cardIndex];
                Storage::updateUserPurchases($purchases);

                // Redirect to main page
                header("Location: main.php");
                exit();

            } else {
                // Display an alert message for insufficient balance
                echo '<script>alert("You do not have enough money. Please make a deposit."); window.location.href = "main.php";</script>';
                exit();
            }
        } else {
            // Log an error message
            error_log('User not found.');

            // Redirect with an error message
            header("Location: main.php?error=User%20not%20found");
            exit();
        }
    } else {
        // Log an error message
        error_log('User not logged in.');

        // Redirect with an error message
        header("Location: main.php?error=User%20not%20logged%20in");
        exit();
    }
} else {
    // Log an error message
    error_log('Invalid request method.');

    // Redirect with an error message
    header("Location: main.php?error=Invalid%20request%20method");
    exit();
}
?>
