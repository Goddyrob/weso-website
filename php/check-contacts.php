<?php
// check-contacts.php - View contact messages
$host = 'localhost';
$dbname = 'weso_db';
$username = 'root';
$password = '';

echo '<!DOCTYPE html>
<html>
<head>
    <title>WESO Contact Messages</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: var(--gold); color: white; position: sticky; top: 0; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f5f5f5; }
        .status-unread { background-color: #fff3cd; }
        .status-read { background-color: #d4edda; }
        .status-replied { background-color: #cce5ff; }
        .status-archived { background-color: #e2e3e5; }
        .success { color: green; }
        .error { color: red; }
        .actions { display: flex; gap: 5px; }
        .action-btn { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
        .view-btn { background: #17a2b8; color: white; }
        .reply-btn { background: #28a745; color: white; }
        .delete-btn { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>WESO Contact Messages</h1>';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p class='success'>✅ Connected to database: $dbname</p>";
    
    // Check if table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'contacts'")->rowCount();
    if ($tableCheck === 0) {
        echo "<p class='error'>❌ Table 'contacts' doesn't exist!</p>";
        echo "<p>Run this SQL to create the table:</p>
        <pre>
        CREATE TABLE contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200),
            message TEXT NOT NULL,
            status ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            replied_at TIMESTAMP NULL,
            replied_by INT DEFAULT NULL,
            reply_message TEXT,
            notes TEXT
        );
        </pre>";
        exit;
    }
    
    echo "<p class='success'>✅ Table 'contacts' exists</p>";
    
    // Count messages
    $total = $pdo->query("SELECT COUNT(*) as count FROM contacts")->fetch()['count'];
    $unread = $pdo->query("SELECT COUNT(*) as count FROM contacts WHERE status = 'unread'")->fetch()['count'];
    
    echo "<div style='background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>
            <strong>Statistics:</strong><br>
            Total Messages: $total<br>
            Unread Messages: <span style='color: " . ($unread > 0 ? 'red' : 'green') . ";'>$unread</span><br>
            Today's Messages: " . $pdo->query("SELECT COUNT(*) as count FROM contacts WHERE DATE(created_at) = CURDATE()")->fetch()['count'] . "
          </div>";
    
    if ($total > 0) {
        $messages = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
        
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message Preview</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>";
        
        foreach ($messages as $row) {
            $statusClass = 'status-' . $row['status'];
            $messagePreview = strlen($row['message']) > 50 ? substr($row['message'], 0, 50) . '...' : $row['message'];
            
            echo "<tr class='$statusClass'>
                    <td>{$row['id']}</td>
                    <td><strong>{$row['name']}</strong></td>
                    <td>{$row['email']}</td>
                    <td>{$row['subject']}</td>
                    <td title='{$row['message']}'>$messagePreview</td>
                    <td>
                        <select onchange='updateStatus({$row['id']}, this.value)'>
                            <option value='unread' " . ($row['status'] == 'unread' ? 'selected' : '') . ">Unread</option>
                            <option value='read' " . ($row['status'] == 'read' ? 'selected' : '') . ">Read</option>
                            <option value='replied' " . ($row['status'] == 'replied' ? 'selected' : '') . ">Replied</option>
                            <option value='archived' " . ($row['status'] == 'archived' ? 'selected' : '') . ">Archived</option>
                        </select>
                    </td>
                    <td>{$row['created_at']}</td>
                    <td class='actions'>
                        <button class='action-btn view-btn' onclick='viewMessage({$row['id']})'>View</button>
                        <button class='action-btn reply-btn' onclick='replyMessage({$row['id']}, \"{$row['email']}\")'>Reply</button>
                        <button class='action-btn delete-btn' onclick='deleteMessage({$row['id']})'>Delete</button>
                    </td>
                  </tr>";
        }
        echo "</table>";
        
        echo "<script>
            function updateStatus(id, status) {
                if(confirm('Change status to ' + status + '?')) {
                    fetch('update-status.php?id=' + id + '&status=' + status)
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                }
            }
            
            function viewMessage(id) {
                window.open('view-message.php?id=' + id, '_blank');
            }
            
            function replyMessage(id, email) {
                const message = prompt('Enter your reply to ' + email + ':');
                if(message) {
                    fetch('reply-message.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: id, reply: message})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            alert('Reply sent!');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                }
            }
            
            function deleteMessage(id) {
                if(confirm('Are you sure you want to delete this message?')) {
                    fetch('delete-message.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                }
            }
        </script>";
    } else {
        echo "<p>No contact messages found in the database.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
    echo "<p>Check your database credentials in contact.php</p>";
}

echo '</div></body></html>';
?>