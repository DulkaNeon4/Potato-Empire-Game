<?php
session_start();
include_once('storage.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle login form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate login credentials
    $user = Storage::getUserByUsername($username);

    if ($user && ($user['isAdmin'] || password_verify($password, $user['password']))) {
        // Valid login
        $_SESSION['username'] = $username;
        $_SESSION['user'] = $user; // Store user details in session

        // Redirect to admin panel if the user is an admin
        if ($user['isAdmin']) {
            header('Location: admin_panel.php');
            exit;
        } else {
            header('Location: main.php');
            exit;
        }
    } else {
        // Invalid login
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 300px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4285f4;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #357ae8;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #4285f4;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <a class="register-link" href="register.php">Don't have an account? Register here.</a>
    </div>
</body>
</html>
