<?php
session_start();
include_once('storage.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle registration form submission
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate form data
    $error = '';
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } elseif (strlen($username) < 5 || strlen($username) > 20) {
        $error = 'Username must be between 5 and 20 characters';
    } elseif (Storage::getUserByUsername($username)) {
        $error = 'Username already exists';
    } else {
        // Register user and redirect to login page
        $newUser = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'isAdmin' => false,
            'money' => 2000, // Initial money for all users
            'cards' => []
        ];

        // Use the public method to add a new user
        Storage::addUser($username, $newUser);

        $_SESSION['username'] = $username;

        header('Location: main.php');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <title>Register</title>
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

        .login-link {
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
        <h1>Register</h1>

        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword" required>

            <button type="submit">Register</button>
        </form>

        <a class="login-link" href="login.php">Already have an account? Login here.</a>
    </div>
</body>
</html>
