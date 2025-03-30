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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['publish']) && isset($_POST['draftCardIndex'])) {
        $draftCardIndex = $_POST['draftCardIndex'];
        $draftCards = Storage::getDraftCards();
        $draftCardData = $draftCards[$draftCardIndex] ?? null;

        if ($draftCardData) {
            // Add the draft card to the cards.json file
            Storage::addCard($draftCardData);

            // Remove the draft card from the draft.json file
            Storage::removeDraftCard($draftCardIndex);

            // Redirect to the draft page after successful publish
            header('Location: view-draft.php');
            exit;
        }
    } elseif (isset($_POST['delete']) && isset($_POST['draftCardIndex'])) {
        // Delete the draft card from the draft.json file
        $draftCardIndex = $_POST['draftCardIndex'];
        Storage::removeDraftCard($draftCardIndex);

        // Redirect to the draft page after successful delete
        header('Location: view-draft.php');
        exit;
    }
}
$elementColors = [
    'fire' => '#FF0000',
    'electric' => '#FFFF00',
    'water' => '#0000FF',
    'grass' => '#00FF00',
    'ice' => '#00FFFF',
    'fighting' => '#A52A2A',
    'poison' => '#800080',
    'ground' => '#D2B48C',
    'flying' => '#87CEEB',
    'psychic' => '#FF1493',
    'bug' => '#008000',
    'rock' => '#A9A9A9',
    'ghost' => '#4B0082',
    'dragon' => '#0000A0',
    'dark' => '#2F4F4F',
    'steel' => '#808080',
    'fairy' => '#FF69B4',
    'normal' => '#A8A77A',
];

// Function to get the background color based on the card type
function getBackgroundColor($type, $elementColors)
{
    return isset($elementColors[$type]) ? $elementColors[$type] : '#FFFFFF';
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

        .admin-panel {
            max-width: 800px;
            margin: 20px;
            margin-left:300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .draft-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 10px; /* Adjust the gap as needed */
        }

        .draft-card {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            width: 200px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .image-container {
            max-width: 100%;
            height: 150px; /* Adjust the height as needed */
            border-radius: 10px;
            overflow: hidden;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .card-info {
            margin-top: 10px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .delete-button,
        .publish-button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>
    <title>View Draft Cards</title>
</head>
<body>
    <header>
        <h3>Pokemon Card Shop - Admin Panel</h3>
        <a class="logout" href="logout.php">Logout</a>
    </header>

    <div class="admin-panel">
        <h2>Draft Cards</h2>
         <a href="admin_panel.php" class="create-new-card-link">Back to Admin Panel</a>
           <a href="add-cards.php" class="create-new-card-link">Create new card</a><br><br>
        <div class="draft-card-container">
            
            <?php
            $draftCards = Storage::getDraftCards();
            
            if (!empty($draftCards)) {
                foreach ($draftCards as $index => $draftCard) {
                    ?>
                    <div class="draft-card">
                        <div class="image-container" style="background-color: <?php echo $elementColors[$draftCard['type']] ?? '#FFFFFF'; ?>">
                            <img src="<?php echo $draftCard['image']; ?>" alt="<?php echo $draftCard['name']; ?>">
                        </div>
                        <div class="card-info">
                            <p><strong>Name:</strong> <?php echo $draftCard['name'] ?></p>
                            <p><strong>Type:</strong> <?php echo $draftCard['type'] ?></p>
                            <p><strong>HP:</strong> <?php echo $draftCard['hp']; ?></p>
                            <p><strong>Attack:</strong> <?php echo $draftCard['attack']; ?></p>
                            <p><strong>Defense:</strong> <?php echo $draftCard['defense']; ?></p>
                            <p><strong>Price:</strong> $<?php echo $draftCard['price']; ?></p>
                            <p><strong>Description:</strong> <?php echo $draftCard['description']; ?></p>
                            <form method='post' action='view-draft.php'>
                                <input type='hidden' name='draftCardIndex' value='<?php echo $index; ?>'>
                                <button type='submit' name='publish' class="publish-button">Publish</button>
                                <button type='submit' name='delete' class="delete-button">Delete</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No draft cards available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
