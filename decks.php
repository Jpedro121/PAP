<?php
// decks.php

// Start the session
session_start();

// Include database connection
require_once ('db.php');

// Fetch decks from the database
$query = "SELECT * FROM decks";
$result = $conn->query($query);

$decks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $decks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>Decks</title>
</head>
<body>
    <h1>Available Decks</h1>
    <?php if (!empty($decks)): ?>
        <ul>
            <?php foreach ($decks as $deck): ?>
                <li><?php echo $deck['name']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No decks available.</p>
    <?php endif; ?>
</body>
</html>