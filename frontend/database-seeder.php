<?php
// C:\xampp\htdocs\Project2\frontend\database-seeder.php
session_start();

$host = 'localhost';
$dbname = 'evenza';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

echo "<div style='font-family: sans-serif; padding: 40px; max-width: 600px; margin: auto; background: #F7F4E9; border-radius: 12px; border: 1px solid #ccc; margin-top: 50px;'>";
echo "<h2 style='color: #282B4A;'>🚀 EVENTRA Simulation Engine</h2>";

try {
    $pdo->beginTransaction();

    // 1. Force all draft events to 'published' so they show up everywhere
    $pdo->exec("UPDATE events SET status = 'published'");
    echo "<p>✅ All events successfully published.</p>";

    // 2. Fetch all available students and events
    $students = $pdo->query("SELECT student_id FROM students")->fetchAll(PDO::FETCH_COLUMN);
    $events = $pdo->query("SELECT event_id, event_type, registration_fee FROM events")->fetchAll(PDO::FETCH_ASSOC);

    $totalReg = 0;
    $totalTeams = 0;
    $teamNames = ['Neon Coders', 'Apex Legends', 'Cyber Knights', 'Quantum Squad', 'Byte Me', 'Runtime Terror', 'Data Pirates', 'The Innovators', 'Syntax Error', 'Tech Titans'];

    foreach ($events as $ev) {
        $e_id = $ev['event_id'];
        $e_type = $ev['event_type'];
        $fee = (float)$ev['registration_fee'];

        // Shuffle all students and pick a random chunk (15 to 35 students per event)
        shuffle($students);
        $participants = array_slice($students, 0, rand(15, 35));

        if ($e_type === 'solo') {
            foreach ($participants as $s_id) {
                // Check if already registered to avoid SQL errors
                $check = $pdo->prepare("SELECT 1 FROM registrations WHERE student_id=? AND event_id=?");
                $check->execute([$s_id, $e_id]);
                
                if (!$check->fetch()) {
                    // Insert Approved Registration with a random date from the past 7 days
                    $pdo->prepare("INSERT INTO registrations (event_id, student_id, registration_type, status, registered_at) VALUES (?, ?, 'solo', 'approved', NOW() - INTERVAL FLOOR(RAND() * 7) DAY)")->execute([$e_id, $s_id]);
                    $reg_id = $pdo->lastInsertId();
                    
                    // Generate Payment
                    $pdo->prepare("INSERT INTO payments (registration_id, amount, payment_status, payment_date) VALUES (?, ?, 'paid', NOW())")->execute([$reg_id, $fee]);
                    $totalReg++;
                }
            }
        } else {
            // Team event: Group the random participants into teams of 3
            $chunks = array_chunk($participants, 3);
            
            foreach ($chunks as $index => $chunk) {
                // We need at least 2 people to form a team
                if (count($chunk) < 2) continue; 

                $leader_id = $chunk[0];
                $team_name = $teamNames[array_rand($teamNames)] . " " . rand(10, 99);
                $team_code = "TM-" . strtoupper(substr(md5(rand()), 0, 5));

                // Create the Team
                $pdo->prepare("INSERT INTO teams (event_id, leader_id, team_name, team_code) VALUES (?, ?, ?, ?)")->execute([$e_id, $leader_id, $team_name, $team_code]);
                $team_id = $pdo->lastInsertId();
                $totalTeams++;

                // Register all members of this team
                foreach ($chunk as $s_id) {
                    $check = $pdo->prepare("SELECT 1 FROM registrations WHERE student_id=? AND event_id=?");
                    $check->execute([$s_id, $e_id]);
                    
                    if (!$check->fetch()) {
                        // Map to team
                        $pdo->prepare("INSERT INTO team_members (team_id, student_id) VALUES (?, ?)")->execute([$team_id, $s_id]);
                        
                        // Create Registration
                        $pdo->prepare("INSERT INTO registrations (event_id, student_id, team_id, registration_type, status, registered_at) VALUES (?, ?, ?, 'team', 'approved', NOW() - INTERVAL FLOOR(RAND() * 7) DAY)")->execute([$e_id, $s_id, $team_id]);
                        $reg_id = $pdo->lastInsertId();
                        
                        // Generate Payment
                        $pdo->prepare("INSERT INTO payments (registration_id, amount, payment_status, payment_date) VALUES (?, ?, 'paid', NOW())")->execute([$reg_id, $fee]);
                        $totalReg++;
                    }
                }
            }
        }
    }

    $pdo->commit();
    echo "<p style='color: green; font-weight: bold;'>✅ Successfully injected <b>$totalReg unique registrations</b>.</p>";
    echo "<p style='color: green; font-weight: bold;'>✅ Successfully formed <b>$totalTeams new teams</b>.</p>";
    echo "<hr style='border:0; border-top:1px solid #ccc; margin: 20px 0;'>";
    echo "<a href='college-dashboard.php' style='display: inline-block; background: #4B4F86; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Return to Dashboard</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<p style='color: red;'>Simulation failed: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>