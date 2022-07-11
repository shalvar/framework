<?php include __DIR__ . '/../header.php'; ?>
<form method="POST" action="/php-framework/comments/<?= $comment->getId()?>/edit">
    <label for="comment">Комментарий</label>
    <textarea name="comment" id="comment" cols="30" rows="5"><?= $comment->getText()?></textarea>
    <button type="submit">Сохранить</button>
</form>
<?php include __DIR__ . '/../footer.php'; ?>