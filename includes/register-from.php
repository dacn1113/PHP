<?php if (!empty($article->errors)) : ?>
<ul>
    <?php foreach ($article->errors as $error) : ?>
    <li><?= $error ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<form method="post">

    <div>
        <label for="username">Username</label>
        <input name="username" id="username" placeholder="Username">
    </div>

    <div>
        <label for="password">Password</label>
        <textarea name="password" rows="4" cols="40" id="password" placeholder="Password"></textarea>
    </div>

    <button>Save</button>

</form>