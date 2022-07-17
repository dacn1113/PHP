<?php

require '../includes/init.php';
Auth::requirelogin();

$conn = require '../includes/db.php';

$articles = Article::getAll($conn);

?>
<?php require '../includes/header.php'; ?>


<h2>Administration</h2>
<?php if (empty($articles)) : ?>
<p>No articles found.</p>
<?php else : ?>

<table>
    <thead>
        <th>title</th>
    </thead>

    <tbody>
        <?php foreach ($articles as $article) : ?>
        <tr>
            <td>
                <a href="article.php?id=<?= $article['id']; ?>"><?= htmlspecialchars($article['title']); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<?php require '../includes/footer.php'; ?>