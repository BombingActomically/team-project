<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = '3306';
$DB_NAME = 'evenza';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO("mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // 1. Check table structure
    $stmt = $pdo->query("DESCRIBE events");
    echo "<h3>Events Table Columns:</h3><pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";

    // 2. Test manual update on the first event
    $test = $pdo->query("SELECT event_id, title, status FROM events LIMIT 1")->fetch();
    if ($test) {
        echo "<h3>Testing Event ID: {$test['event_id']} ({$test['title']})</h3>";
        echo "Current Status: <b>{$test['status']}</b><br>";
        
        $newStatus = ($test['status'] === 'active') ? 'inactive' : 'active';
        $update = $pdo->prepare("UPDATE events SET status = ? WHERE event_id = ?");
        $update->execute([$newStatus, $test['event_id']]);
        
        echo "Successfully toggled database status to: <b>$newStatus</b>! Check your database and refresh this page to see if it sticks.";
    } else {
        echo "No events found in the database table!";
    }

} catch (Exception $e) {
    echo "<h3>DATABASE ERROR:</h3> " . $e->getMessage();
}