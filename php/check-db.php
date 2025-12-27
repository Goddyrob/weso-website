<?php
// check-db.php - View registrations
require __DIR__ . '/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM registrations ORDER BY created_at DESC");
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h1>Registrations</h1>";
    echo "<p>Total: " . count($registrations) . "</p>";
    
    if (count($registrations) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>Church</th><th>Interest</th><th>Created</th></tr>";
        foreach ($registrations as $reg) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($reg['id']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['email_address']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['phone_number']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['gender']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['church_fellowship']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['ministry_interest']) . "</td>";
            echo "<td>" . htmlspecialchars($reg['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No registrations yet.</p>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>