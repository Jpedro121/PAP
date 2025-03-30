<?php
// decks.php

// Start the session
session_start();

// Include database connection
require_once 'db_connection.php';

// Fetch decks from the database
$query = "SELECT * FROM decks";
$result = $conn->query($query);

// Check if decks exist
$decks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $decks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decks</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Available Decks</h1>
    <?php if (!empty($decks)): ?>
        <ul>
            <?php foreach ($decks as $deck): ?>
                <li><?php echo htmlspecialchars($deck['name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No decks available.</p>
    <?php endif; ?>
</body>
</html>