<?php
session_start();
include_once('storage.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Check if the logged-in user is an admin
if (!isset($_SESSION['user']['isAdmin']) || !$_SESSION['user']['isAdmin']) {
    header('Location: main.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $requiredFields = ['name', 'type', 'hp', 'attack', 'defense', 'price', 'description', 'image'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Please enter a value for $field.";
        }
    }

    if (empty($errors)) {
        $newCard = [
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'hp' => $_POST['hp'],
            'attack' => $_POST['attack'],
            'defense' => $_POST['defense'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
            'image' => $_POST['image'],
            'owner' => null // Initially not owned by anyone
        ];

        // Get the existing draft cards, add the new card, and save the data
        $existingDraftCards = Storage::getDraftCards();
        $existingDraftCards[] = $newCard;
        Storage::setDraftCards($existingDraftCards);

        Storage::saveData();

        // Redirect to admin panel after successful card creation
        header('Location: view-draft.php');
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
    <title>Add New Card</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        h1 {
            margin: 0;
            padding-left: 15px;
            color: #fff;
        }

        .user-info {
            margin-right: 15px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .user-info span {
            margin-right: 10px;
            color: #fff;
        }

        .admin-panel {
            max-width: 800px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .create-card-form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-row label {
            flex-basis: calc(50% - 10px);
            margin-bottom: 5px;
            color: #333;
        }

        .form-row select,
        .form-row input {
            flex-basis: calc(50% - 10px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-row button {
            background-color: #4285f4;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            margin-left: 500px;
        }

        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Pokesman Card Shop</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['username']; ?> - </span>
            <a href="logout.php" style="color:#fff">Logout</a>
        </div>
    </header>

    <div class="admin-panel">
        <h2>Add New Card</h2>
         
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form class="create-card-form" style="width:700px;" action="add-cards.php" method="post">
            <div class="form-row">
                <label for="name">Card Name:</label>
                <input type="text" name="name" required>
           
                <label for="type">Type:</label>
                <select name="type" required>
                   
                    <option value="fire">Fire</option>
                    <option value="water">Water</option>
                    <option value="grass">Grass</option>
                    <option value="electric">Electric</option>
                    <option value="ground">Ground</option>
                    <option value="ice">Ice</option>
                    <option value="poison">Poison</option>
                    <option value="flying">Flying</option>
                    <option value="psychic">Psychic</option>
                    <option value="fighting">Fighting</option>
                    <option value="ghost">Ghost</option>
                    <option value="rock">Rock</option>
                    <option value="dark">Dark</option>
                    <option value="steel">Steel</option>
                    <option value="bug">Bug</option>
                    <option value="normal">Normal</option>
                    <option value="dragon">Dragon</option>
                    <option value="fairy">Fairy</option>
                </select>
            </div>
                    
            <div class="form-row">
                <label for="hp">HP:</label>
                <input type="number" name="hp" required>
                   
                <label for="attack">Attack:</label>
                <input type="number" name="attack" required>
            </div>

            <div class="form-row">
                <label for="defense">Defense:</label>
                <input type="number" name="defense" required>
          
                <label for="price">Price:</label>
                <input type="number" name="price" required>
            </div>

            <div class="form-row">
                <label for="description">Description:</label>
                <input type="text" name="description" required>
          
                <label for="image">Image URL:</label>
                <input type="text" name="image" required>
              </div>

            <div class="form-row">
                
            <input type="hidden" name="action" value="createCard">
            
            <button type="submit">Add Card</button>
            </div>
            <a href="admin_panel.php" class="create-new-card-link">Back to Admin Panel</a>
        </form>
    </div>
</body>
</html>
