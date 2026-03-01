<?php
// Connect to the database
$host = 'localhost';
$db = 'ctf_db';
$user = 'root'; // As configured by provision.sh
$pass = '';     // Provision.sh might set password or leave empty. 
                // We default to checking empty, but if password is set via ENV, we'd use it.
                // For this basic check, we'll try without password.
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Basic error handling for the test page
    die("Database connection failed. Did you run 'vagrant up'? Error: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF Base Architecture</title>
    <!-- Basic framework: Pico CSS for clean out-of-the-box styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>
    <main class="container">
        <h1>Welcome to the CTF Base Setup</h1>
        <p>This is a default validation page to confirm everything is working nicely.</p>
        
        <article>
            <header>
                <hgroup>
                    <h2>Database Verification</h2>
                    <p>Retrieving initial data injected during Vagrant up...</p>
                </hgroup>
            </header>
            
            <figure>
                <table role="grid">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $pdo->query('SELECT id, username, email, role FROM users LIMIT 10');
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                echo "<th scope='row'>" . htmlspecialchars($row['id']) . "</th>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                echo "</tr>";
                            }
                        } catch (\PDOException $e) {
                            echo "<tr><td colspan='4'>Error fetching data: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </figure>
            
            <footer>
                <small>Connected to MySQL database instances successfully.</small>
            </footer>
        </article>
    </main>
</body>
</html>
