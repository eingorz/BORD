<!DOCTYPE html>
<html>
<head>
    <title>BORD - Homepage</title>
</head>
<body>
    <h1>Welcome to BORD</h1>
    <h3>Available Boards</h3>
    <ul>
        <?php foreach ($boards as $board): ?>
            <li>
                <!-- This creates a link like <a href="/g/">/g/ - Technology</a> -->
                <a href="/<?php echo htmlspecialchars($board['shortname']); ?>/">
                    /<?php echo htmlspecialchars($board['shortname']); ?>/ - <?php echo htmlspecialchars($board['longname']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>