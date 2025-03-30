<?php
include_once('storage.php');

$index = isset($_GET['index']) ? $_GET['index'] : null;
$card = Storage::getCardByIndex($index);

if (!$card) {
    // Redirect or show an error message if the card doesn't exist
    header('Location: main.php');
    exit;
}

// Define background colors based on the monster's element
$elementColors = [
    'fire' => '#FF0000',      // Red for Fire
    'electric' => '#FFFF00',  // Yellow for Electric
    'water' => '#0000FF',     // Blue for Water
    'grass' => '#00FF00',     // Green for Grass
    'ice' => '#00FFFF',       // Cyan for Ice
    'fighting' => '#A52A2A',  // Brown for Fighting
    'poison' => '#800080',    // Purple for Poison
    'ground' => '#D2B48C',    // Tan for Ground
    'flying' => '#87CEEB',    // Light Blue for Flying
    'psychic' => '#FF1493',   // Deep Pink for Psychic
    'bug' => '#008000',       // Dark Green for Bug
    'rock' => '#A9A9A9',      // Dark Gray for Rock
    'ghost' => '#4B0082',     // Indigo for Ghost
    'dragon' => '#0000A0',    // Dark Blue for Dragon
    'dark' => '#2F4F4F',      // Dark Slate Gray for Dark
    'steel' => '#808080',     // Gray for Steel
    'fairy' => '#FF69B4',     // Pink for Fairy
    'normal' => '#A8A77A',    // Normal
    'fighting' => '#C22E28',  // Fighting
    'flying' => '#A98FF3',    // Flying
    'poison' => '#A33EA1',    // Poison
    'ground' => '#E2BF65',    // Ground
    'rock' => '#B6A136',      // Rock
    'bug' => '#A6B91A',       // Bug
    'ghost' => '#735797',     // Ghost
    'steel' => '#B7B7CE',     // Steel
    'fire' => '#EE8130',      // Fire
    'water' => '#6390F0',     // Water
    'grass' => '#7AC74C',     // Grass
    'electric' => '#F7D02C',  // Electric
    'psychic' => '#F95587',   // Psychic
    'ice' => '#96D9D6',       // Ice
    'dragon' => '#6F35FC',    // Dragon
    'dark' => '#705746',      // Dark
    'fairy' => '#D685AD',     // Fairy
];

$imageBackgroundColor = isset($elementColors[$card['type']]) ? $elementColors[$card['type']] : '#FFFFFF';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
              font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .card-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        img {
            max-width: 40%;
            width: 30%;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: <?php echo $imageBackgroundColor; ?>;
            transition: background-color 0.3s;
        }

        .details-container {
            max-width: 40%;
            width: 100%;
            text-align: left;
        }

        h2 {
            color: #555;
        }

        p {
            color: #777;
            margin-bottom: 10px;
            display: block;
        }

        strong {
            color: #333;
            font-weight: bold;
        }

        a {
            display: inline-block;
            padding: 7px 15px;
            background-color: #4285f4;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #3367d6;
        }
    </style>
    <title>Card Details</title>
</head>
<body>
    <h1>Card Details</h1>

    <div class="card-container">
        <img src="<?php echo $card['image']; ?>" alt="<?php echo $card['name']; ?>">
        <div class="details-container">
            <h2><?php echo $card['name']; ?></h2>
            <p><strong>Element:</strong> <?php echo $card['type']; ?></p>
            <p><strong>HP:</strong> <?php echo $card['hp']; ?></p>
            <p><strong>Attack:</strong> <?php echo $card['attack']; ?></p>
            <p><strong>Defense:</strong> <?php echo $card['defense']; ?></p>
            <p><strong>Description:</strong> <?php echo $card['description']; ?></p>
        </div>
    </div>

    <a href="main.php">Back to Main</a>

    <script>
        // Implement any JavaScript for the card details page
    </script>
</body>
</html>
