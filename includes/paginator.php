<?php $base = strtok($_SERVER['REQUEST_URI'], '?'); ?>
<nav>
    <ul>
        <li>
            <a href="<?= $base; ?>?page=<?= $paginator->previous; ?>">Previous</a>
        </li>
        <li>
            <a href="<?= $base; ?>?page=<?= $paginator->next; ?>">Next</a>
        </li>
    </ul>
</nav>