<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $article = Article::getWihtCategories($conn, $_GET['id']);
} else {
    $article = null;
}

?>
<?php require 'includes/header.php'; ?>

<?php if ($article) : ?>

<article>
    <h2><?= htmlspecialchars($article[0]['title']); ?></h2>

    <?php if ($article[0]['category_name']) : ?>
    <p>Categories:
        <?php foreach ($article as $a) : ?>
        <?= htmlspecialchars($a['category_name']); ?>
        <?php endforeach; ?>
    </p>
    <? endif; ?>

    <?php if ($article[0]['image_file']) : ?>
    <img src="/upload/<?= $article[0]['image_file']; ?>">
    <?php endif; ?>

    <p><?= htmlspecialchars($article[0]['content']); ?></p>
</article>

<?php else : ?>
<p>Article not found.</p>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>