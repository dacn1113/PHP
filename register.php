<?php

require 'includes/init.php';
$article = new User();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = require 'includes/db.php';

    if (User::checkauthor($conn, $_POST['username'], $_POST['password'])) {

        $error = "Username ivalid";
    } else {

        $article->username = $_POST['username'];
        $article->password = $_POST['password'];

        $article->adduser($conn);

        Url::redirect('/login.php');
    }
}

?>
<?php require 'includes/header.php'; ?>

<h2>Register</h2>

<?php if (!empty($error)) : ?>
<p><?= $error ?></p>
<?php endif; ?>

<form method="post">

    <div>
        <label for="username">Username</label>
        <input name="username" id="username">
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <button>Log in</button>

</form>

<?php require 'includes/footer.php'; ?>