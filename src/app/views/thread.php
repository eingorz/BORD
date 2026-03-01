<!DOCTYPE html>
<html>
<head>
    <title>Thread #<?php echo htmlspecialchars($post['id']); ?></title>
</head>
<body>
    <a href="/">[Home]</a>
    <a href="/<?php echo htmlspecialchars($shortname); ?>/">[Return to /<?php echo htmlspecialchars($shortname); ?>/]</a>
    <hr>
    
    <h2>Reply to Thread</h2>
    <form method="POST" action="/<?php echo htmlspecialchars($shortname); ?>/thread/<?php echo $post['id']; ?>/reply">
        <textarea name="content" required></textarea><br>
        <button type="submit">Submit Reply</button>
    </form>
    <hr>

    <!-- Original Post -->
    <div style="border: 2px solid darkblue; margin-bottom: 20px; padding: 10px; background-color: #f0f0f0;">
        <p><strong>Thread ID: <?php echo htmlspecialchars($post['id']); ?></strong> | <em>Posted: <?php echo htmlspecialchars($post['timestamp']); ?></em></p>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    </div>

    <!-- Replies -->
    <?php if (empty($replies)): ?>
        <p><em>No replies yet. Be the first to reply!</em></p>
    <?php else: ?>
        <?php foreach ($replies as $reply): ?>
            <div style="border: 1px solid black; margin-bottom: 10px; margin-left: 40px; padding: 10px;">
                <p><strong>Reply ID: <?php echo htmlspecialchars($reply['id']); ?></strong> | <em>Posted: <?php echo htmlspecialchars($reply['timestamp']); ?></em></p>
                <p><?php echo nl2br(htmlspecialchars($reply['content'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
