<?php
session_start();
include_once('storage.php');

// Get user details
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = Storage::getUserByUsername($username);

    if (!$user) {
        // Redirect to login if user is not found
        header("Location: login.php");
        exit();
    }

    // Get user cards
    $userCards = Storage::getUserCards($user);

    // Get admin user
    $adminUser = Storage::getUserByUsername('admin');

    // Get all cards
    $cards = Storage::getCards();
}

// Function to calculate the selling price (90% of the original price)
function calculateSellingPrice($price) {
    return $price * 0.9;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
           <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <title>User Details</title>
</head>
<body>
  <header>
        <h3>Pokemon Card Shop</h3>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="welcome">
                    <p><strong>Logged in as : </strong><?php echo $_SESSION['username']; ?></p>
                    
                    <!-- Display "Go to Admin Panel" link for admins -->
                    <?php if (!$user['isAdmin']): ?>
                        <a class="logout" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a class="admin-panel-link" href="admin_panel.php">Go to Admin Panel</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <a class="login-button" href="login.php">Login</a>
                <a class="signup-button" href="register.php">Signup</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="user-details-container">
        <h2>User Details -  <a href="main.php" style="font-size: 18px;">Back to Home</a></h2>
       
        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
        
        <!-- Display email only if the user is not an admin -->
        <?php if (!$user['isAdmin']): ?>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <?php endif; ?>

        <p><strong>Available Balance:</strong> $<?php echo $user['money']; ?></p>
                
        <!-- Display "Your Cards" section for non-admin users -->
        <?php if (!$user['isAdmin']): ?>
            <h2>Your Cards</h2>
            <ul class="user-cards-list" style="width:700px;">
                <?php foreach ($userCards as $cardIndex): ?>
                    <li>
                        <img src="<?php echo $cards[$cardIndex]['image']; ?>" alt="<?php echo $cards[$cardIndex]['name']; ?>">
                        <p><strong><?php echo $cards[$cardIndex]['name']; ?></strong></p>
                        <p>Price: $<?php echo $cards[$cardIndex]['price']; ?></p>
                        <form action="sell-card.php" method="post">
                            <input type="hidden" name="cardIndex" value="<?php echo $cardIndex; ?>">
                            <input type="hidden" name="sellingPrice" value="<?php echo $cards[$cardIndex]['price']; ?>">
                            <button type="submit">Sell to Admin</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>

    <script>
        // You can implement sellCard logic using AJAX or form submission
        // Update user and card data accordingly
    </script>
</body>
</html>

<style>
        body {
           font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        header {
            background-color: #333;
            padding: 10px;
            color: #fff;
            text-align: left;
            width: 100%;
            height: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h3 {
            margin: 0;
            color: #fff;
        }

        .auth-buttons {
            display: flex;
            align-items: center;
        }

        .welcome {
            text-align: right;
        }

        .welcome p {
            margin: 0;
            display: inline-block;
            color: #fff;
        }

        .logout,
        .login-button,
        .signup-button,
        button {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }

        .logout {
            background-color: #d9534f;
        }

        .login-button,
        .signup-button,
        button {
            background-color: #5bc0de;
        }

        .user-details-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .user-details-container h2 {
            color: #333;
        }

        .user-details-container p {
            margin: 10px 0;
        }

        .user-cards-list li {
            border: 1px solid #ccc;
           
            text-align: center;
            max-width: calc(33% - 20px); /* Set the maximum width for each card */
            background-color: #f5f5f5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            transition: transform 0.3s ease-in-out;
        }

        .user-cards-list p {
            margin: 10px 0;
        }

        .user-cards-list button {
            background-color: #5bc0de;
            color: #fff;
        }
    </style>