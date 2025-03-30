<?php
session_start();
include_once('storage.php');

$cards = Storage::getCards();

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

// Filter cards based on selected type
$filteredCards = isset($_POST['filterType']) && $_POST['filterType']
    ? array_filter($cards, function ($card) {
        return strtolower($card['type']) === $_POST['filterType'];
    })
    : $cards;


$cardsPerPage = 8;
$totalCards = count($filteredCards);
$totalPages = ceil($totalCards / $cardsPerPage);
$currentPage = isset($_GET['page']) && $_GET['page'] >= 1 && $_GET['page'] <= $totalPages ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $cardsPerPage;
$displayedCards = array_slice($filteredCards, $offset, $cardsPerPage);

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

        .welcome {
            text-align: right;
        }

        .welcome p {
            margin: 0;
            display: inline-block;
            color: #fff;
        }

        .logout {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 5px 10px;
            border: 1px solid #fff;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }

        .auth-buttons {
            display: flex;
            align-items: center;
        }

        .login-button,
        .signup-button {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 5px 10px;
            border: 1px solid #fff;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }

        ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the cards */
        }

        li {
            border: 1px solid #ccc;
            margin: 10px;
            padding: 10px;
            text-align: center;
            width: calc(20% - 20px); /* Adjust the width of each card */
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            border-radius: 10px; /* Rounded corners */
            overflow: hidden; /* Hide overflow content */
        }

        li:hover {
            transform: scale(1.05);
        }

        .card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .image-container {
            max-width: 100%;
            height: auto;
            border-radius: 10px; /* Rounded corners for image */
            overflow: hidden; /* Hide overflow content */
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px; /* Rounded corners for image */
        }

        .buy-button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 5px 30px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
        }

        .details-link {
            display: block;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .details-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .details-container p {
            margin-right: 20px;
            font-size: 15px; /* Adjust text size */
        }

        .filter-form {
            margin-top: 20px;
            text-align: center;
        }

        .filter-form label {
            margin-right: 10px;
        }

        .filter-form select {
            padding: 5px;
        }

        .filter-form button {
            padding: 5px 10px;
            background-color: #0080d1;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
    <title>Main Page</title>
</head>
<body>
    <header>
        <h3>Pokemon Card Shop</h3>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="welcome">
                    <p><b><a href="user-details.php"> <?php echo $_SESSION['username']; ?></a></b></p>
                    <a class="logout" href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <a class="login-button" href="login.php">Login</a>
                <a class="signup-button" href="register.php">Signup</a>
            <?php endif; ?>
        </div>
    </header>

<!-- Filter form -->
<form class="filter-form" action="main.php" method="post">
    <label for="filterType">Filter by Type:</label>
    <select id="filterType" name="filterType">
        <option value="">All Types</option>
        <?php foreach ($elementColors as $type => $color): ?>
            <option value="<?php echo $type; ?>"><?php echo ucfirst($type); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Apply Filter</button>
</form>

<!-- List Pokemon cards -->
<?php if (empty($displayedCards)): ?>
    <p style="color:red;font-weight:bold;font-size:15px;margin-left:580px;">There is no card found for this search. Try another.</p>
<?php else: ?>
    <ul>
        <?php foreach ($displayedCards as $index => $card): ?>
            <li>
                <div class="card-container">
                    <div class="image-container" style="background-color: <?php echo $elementColors[$card['type']] ?? '#FFFFFF'; ?>">
                        <a href="card-details.php?index=<?php echo $index; ?>"><img src="<?php echo $card['image']; ?>" alt="<?php echo $card['name']; ?>"></a>
                    </div>
                    <p style="color:#0080d1"><strong><?php echo $card['name']; ?></strong></p>
                    <span style="text-transform: lowercase;">
                        </i>🏷️ <?php echo $card['type']; ?>
                    </span>

                    <div class="details-container">
                        <p>❤️ <?php echo $card['hp']; ?></p>
                        <p>⚔️ <?php echo $card['attack']; ?></p>
                        <p>🛡️ <?php echo $card['defense']; ?></p>
                    </div>

                    <?php
                    if (
                        isset($_SESSION['username']) &&
                        $_SESSION['username'] !== 'admin' &&
                        ($user = Storage::getUserByUsername($_SESSION['username'])) &&
                        is_array($user['cards']) && // Check if user['cards'] is an array
                        !in_array($index, $user['cards'])
                    ):
                        ?>
                       <?php
    $isPurchased = in_array($index, $user['cards']);
    if ($isPurchased) {
        echo '<button class="buy-button" disabled>Purchased</button>';
    } else {
        echo '<form action="buy-card.php" method="post">';
        echo '<input type="hidden" name="index" value="' . $index . '">';
        echo '<button class="buy-button" type="submit">Buy</button>';
        echo '</form>';
    }
    ?>
                    <?php endif; ?>

                    <p><strong>💲<?php echo $card['price']; ?></strong></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>



<?php endif; ?>
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
</body>
</html>
