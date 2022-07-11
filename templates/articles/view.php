<?php include __DIR__ . '/../header.php'; ?>
  <h1><?= $article->getName() ?></h1>
  <p><?= $article->getText() ?></p>
  <p>Автор: <?= $article->getAuthor()->getNickname() ?></p>

<form method="POST" action="/php-framework/articles/<?= $article->getId()?>/comments">
    <label for="comment">Комментарий</label>
    <textarea name="comment" id="comment" cols="30" rows="5"></textarea>
    <button type="submit">Отправить</button>
</form>
<h2>Комментарии к статье</h2>
<ul>
  <?php
  foreach($comments as $comment){
      echo "<li id='comment".$comment->getId()."'>".$comment->getText()."<br>Автор: ".$comment->getAuthor()->getNickname()." <a href='/php-framework/comments/".$comment->getId()."/edit'>Редактировать</a></li>";
  }
  ?>
</ul>
<?php include __DIR__ . '/../footer.php'; ?>