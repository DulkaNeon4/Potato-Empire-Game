<?php
session_start();
include_once('storage.php');

$cardsPerPage = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$cards = Storage::getCards(); // Retrieve all cards (you may need to adjust this based on your storage)

// Paginate the cards
$totalCards = count($cards);
$totalPages = ceil($totalCards / $cardsPerPage);

$startIndex = ($page - 1) * $cardsPerPage;
$endIndex = min($startIndex + $cardsPerPage - 1, $totalCards - 1);

$paginatedCards = array_slice($cards, $startIndex, $endIndex - $startIndex + 1);

echo json_encode([
    'cards' => $paginatedCards,
    'totalPages' => $totalPages,
]);
