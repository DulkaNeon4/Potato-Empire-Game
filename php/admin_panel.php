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

// Handle card deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteCard') {
    $indexToDelete = $_POST['index'];

    // Get the existing cards, remove the specified card, and save the data
    $existingCards = Storage::getCards();
    unset($existingCards[$indexToDelete]);
    Storage::setCards(array_values($existingCards)); // Update the cards array

    Storage::saveData();
}

// Handle card publication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'publishCard') {
    $indexToPublish = $_POST['index'];

    // Get the existing cards from draft, remove the specified card, and save the data
    $draftCards = Storage::getCards(); // Assuming draft cards are in the same array as published cards
    $publishedCard = $draftCards[$indexToPublish];
    unset($draftCards[$indexToPublish]);
    Storage::setCards(array_values($draftCards)); // Update the cards array

    // Get the existing published cards, add the published card, and save the data
    $publishedCards = Storage::getCards();
    $publishedCards[] = $publishedCard;
    Storage::setCards($publishedCards);

    Storage::saveData();
}

// Handle card creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'createCard') {
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

    // Get the existing cards, add the new card, and save the data
    $existingCards = Storage::getCards();
    $existingCards[] = $newCard;
    Storage::$cards = $existingCards;

    Storage::saveData();
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


$cardsPerPage = 6;
// Get the total number of cards
$totalCards = count(Storage::getCards());

// Calculate the total number of pages
$totalPages = ceil($totalCards / $cardsPerPage);

// Get the current page from the query parameters
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Ensure the current page is within valid range
$currentPage = max(1, min($totalPages, $currentPage));

// Calculate the index for the starting card on the current page
$startIndex = ($currentPage - 1) * $cardsPerPage;

// Get the cards to display on the current page
$currentCards = array_slice(Storage::getCards(), $startIndex, $cardsPerPage);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Admin Panel</title>
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

        .admin-panel {
            max-width: 800px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            cursor: pointer;
            position: relative;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .card-info {
            padding: 15px;
            text-align: center;
        }

        .remove-button,
        .publish-button {
            background-color: #d9534f;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .publish-button {
            background-color: #5bc0de;
        }

        .create-new-card-link {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            text-align: center;
            color: #4285f4;
            text-decoration: none;
        }
.pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            color: #0080d1;
            background-color: #f2f2f2;
            border-radius: 5px;
            margin: 0 5px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination a.active {
    background-color: #0080d1;
    color: white;
}
    </style>
</head>

<body>
    <header>
        <h1>Pokemon Card Shop</h1>
        <div style="margin-right: 15px; font-weight: bold;">
            <span><?php echo $_SESSION['username']; ?> - <a href="logout.php"
                    style="color: #fff;">Logout</a></span>
        </div>
    </header>

    <div class="admin-panel">
        <!-- Display cards -->
        <h2>Published Card List</h2>
        <a href="add-cards.php" class="create-new-card-link">Create new card</a>
        <a href="view-draft.php" class="create-new-card-link">View Drafts</a>
          <div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="?page=<?php echo $currentPage - 1; ?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?php echo $currentPage + 1; ?>">&gt;</a>
    <?php endif; ?>
</div>
        <div class="card-list">
            
               <?php foreach ($currentCards as $index => $card): ?>
                <div class="card">
                     <div class="image-container" style="background-color: <?php echo $elementColors[$card['type']] ?? '#FFFFFF'; ?>">
                       <img src="<?php echo $card['image']; ?>" alt="<?php echo $card['name']; ?>">
                    </div>
                       
                    <div class="card-info">
                        <p><strong>Name:</strong> <?php echo $card['name']; ?></p>
                        <p><strong>Type:</strong> <?php echo $card['type']; ?></p>
                        <p><strong>HP:</strong> <?php echo $card['hp']; ?></p>
                        <p><strong>Attack:</strong> <?php echo $card['attack']; ?></p>
                        <p><strong>Defense:</strong> <?php echo $card['defense']; ?></p>
                        <p><strong>Price:</strong> $<?php echo $card['price']; ?></p>
                        <p><strong>Description:</strong> <?php echo $card['description']; ?></p>
                        <form action="admin_panel.php" method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="action" value="deleteCard">
                            <button class="remove-button" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
                
            <?php endforeach; ?>
            
        </div>
    </div>
</body>

</html>
