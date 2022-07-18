<?php
phpinfo();
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
    try {
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file uploaded');
                break;
            default:
                throw new Exception('An error occurred');
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>
<?php require '../includes/header.php'; ?>

<h2>Edit article image</h2>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="file">Image file</label>
        <input type="file" name="file" id="file">
    </div>
    <button>Upload</button>
</form>

<?php require '../includes/footer.php'; ?>