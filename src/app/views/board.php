<!DOCTYPE html>
<html>
<head>
    <title>/<?php echo htmlspecialchars($board['shortname']); ?>/ - <?php echo htmlspecialchars($board['longname']); ?></title>
</head>
<body>
    <a href="/">[Home]</a>
    <h1>/<?php echo htmlspecialchars($board['shortname']); ?>/ - <?php echo htmlspecialchars($board['longname']); ?></h1>

    <hr>
    <!-- We will build the submission form here later -->
    <h3>Submit a New Thread</h3>
    <form method="POST" action="/<?php echo htmlspecialchars($board['shortname']); ?>/submit">
        <textarea name="content" required></textarea><br>
        <button type="submit">Post</button>
    </form>
    <hr>

    <!-- Loop through formatting the threads -->
    <?php if (empty($posts)): ?>
        <p>There are no threads on this board yet. Be the first!</p>
    <?php else: ?>
        <?php foreach ($posts as $thread): ?>
            <div style="border: 1px solid black; margin-bottom: 10px; padding: 10px;">
                <p><strong>Thread ID: <?php echo $thread['id']; ?></strong> | <em>Bumped: <?php echo $thread['bumptimestamp']; ?></em></p>
                <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
                <a href="/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>">View Thread / Reply</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
