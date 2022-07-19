<?php

require '../includes/init.php';
Auth::requirelogin();
$conn = require '../includes/db.php';

if (isset($_GET['id'])) {

    $article = Article::getByID($conn, $_GET['id']);

    if (!$article) {
        die("article not found");
    }
} else {
    die("id not supplied, article not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $previou_image = $article->image_file;
    if ($article->setImageFile($conn, null)) {

        if ($previou_image) {
            unlink("../upload/$previou_image");
        }
        Url::redirect("/admin/edit-article-image.php?id={$article->id}");
    }
}
?>
<?php require '../includes/header.php'; ?>

<h2>Delete article image</h2>

<form method="post">
    <p>Are you sure?</p>
    <button>Delete</button>
    <a href="edit-article-image.php?=<?= $article->id; ?>">Cancel</a>
</form>

<?php require '../includes/footer.php'; ?>