<?php
class Storage {

    private static $users = []; // User data storage
    private static $cards = []; // Pokemon card data storage
    private static $draftCards = []; // Draft card data storage

    private static $usersFile = 'users.json';
    private static $cardsFile = 'cards.json';
    private static $draftCardsFile = 'draft.json';
    private static $userPurchasesFile = 'user-purchases.json';

    public static function setCards($cards) {
        self::$cards = $cards;
    }

    private static function readFile($file)
    {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }

    public static function getadminCards() {
        return self::$cards;
    }

    private static function writeFile($file, $data)
    {
        $content = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($file, $content);
    }

    public static function updateUser($userData)
    {
        $users = self::readFile(self::$usersFile);
        $username = $userData['username'];

        if (isset($users[$username])) {
            $users[$username] = $userData;
            self::writeFile(self::$usersFile, $users);
            return true;
        }

        return false;
    }

    public static function updateCard($cardIndex, $cardData)
    {
        $cards = self::readFile(self::$cardsFile);

        if (isset($cards[$cardIndex])) {
            $cards[$cardIndex] = $cardData;
            self::writeFile(self::$cardsFile, $cards);
            return true;
        }

        return false;
    }

    public static function getUserPurchases()
    {
        $data = self::readFile(self::$userPurchasesFile);
        return $data['purchases'] ?? [];
    }

    public static function updateUserPurchases($purchases)
    {
        $data = ['purchases' => $purchases];
        self::writeFile(self::$userPurchasesFile, $data);
    }

    public static function addUser($username, $userData) {
        self::loadUsers();
        self::$users[$username] = $userData;
        self::saveData();
    }

    public static function loadUsers() {
        $userData = json_decode(file_get_contents(__DIR__ . '/users.json'), true);
        self::$users = is_array($userData) ? $userData : [];
    }

    public static function getAllUsers() {
        self::loadUsers();
        return self::$users;
    }

    public static function getUserByUsername($username) {
        $allUsers = self::getAllUsers();
        return isset($allUsers[$username]) ? $allUsers[$username] : null;
    }

    public static function init() {
        if (file_exists(__DIR__ . '/users.json') && file_exists(__DIR__ . '/cards.json')) {
            self::loadUsers();

            $cardsData = file_get_contents(__DIR__ . '/cards.json');

            if (!empty($cardsData)) {
                self::$cards = json_decode($cardsData, true);
                return;
            }
        }

        self::$users = [
            'admin' => [
                'username' => 'admin',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'isAdmin' => true,
                'money' => 1000,
                'cards' => []
            ]
        ];

        self::$cards = [
            [
                'name' => 'Pikachu',
                'type' => 'electric',
                'hp' => 60,
                'attack' => 20,
                'defense' => 20,
                'price' => 160,
                'description' => 'Pikachu that can generate powerful electricity...',
                'image' => 'https://assets.pokemon.com/assets/cms2/img/pokedex/full/025.png',
                'owner' => null
            ]
            // Add more cards...
        ];

        self::saveData();
    }

    public static function getUsers() {
        return self::$users;
    }

    public static function getCards() {
        return self::$cards;
    }

    public static function getUserCards($user) {
        return isset($user['cards']) ? $user['cards'] : [];
    }

    public static function getCardByIndex($index) {
        return isset(self::$cards[$index]) ? self::$cards[$index] : null;
    }

    public static function updateUserCards($username, $userCards)
    {
        $users = self::readFile(self::$usersFile);

        if (isset($users[$username])) {
            $users[$username]['cards'] = $userCards;
            self::writeFile(self::$usersFile, $users);
            return true;
        }

        return false;
    }

    public static function updateUserMoney($username, $newBalance)
    {
        $users = self::readFile(self::$usersFile);

        if (isset($users[$username])) {
            $users[$username]['money'] = $newBalance;
            self::writeFile(self::$usersFile, $users);
            return true;
        }

        return false;
    }

    public static function isAdmin($username) {
        $user = self::getUserByUsername($username);
        return $user && isset($user['isAdmin']) && $user['isAdmin'];
    }

    public static function updateDraftCard($draftCardIndex, $draftCardData)
    {
        $draftCards = self::readFile(self::$draftCardsFile);

        if (isset($draftCards[$draftCardIndex])) {
            $draftCards[$draftCardIndex] = $draftCardData;
            self::writeFile(self::$draftCardsFile, $draftCards);
            return true;
        }

        return false;
    }

    public static function getDraftCards()
    {
        $data = self::readFile(self::$draftCardsFile);
        return $data['draftCards'] ?? [];
    }

    public static function setDraftCards($draftCards)
    {
        $data = ['draftCards' => $draftCards];
        self::writeFile(self::$draftCardsFile, $data);
    }

    public static function addDraftCard($draftCardData)
    {
        $draftCards = self::getDraftCards();
        $draftCards[] = $draftCardData;
        self::setDraftCards($draftCards);
    }

    public static function removeDraftCard($draftCardIndex)
    {
        $draftCards = self::getDraftCards();

        if (isset($draftCards[$draftCardIndex])) {
            unset($draftCards[$draftCardIndex]);
            self::setDraftCards(array_values($draftCards));
            return true;
        }

        return false;
    }

    public static function saveData() {
        file_put_contents(__DIR__ . '/users.json', json_encode(self::$users, JSON_PRETTY_PRINT));
        file_put_contents(__DIR__ . '/cards.json', json_encode(self::$cards, JSON_PRETTY_PRINT));
    }

    // Add new card to the cards array
    public static function addCard($cardData)
    {
        self::$cards[] = $cardData;
        self::saveData();
    }
}

// Initialize data
Storage::init();
?>
