<?php

namespace MyProject\Models\Comments;
use MyProject\Models\Users\User;
use MyProject\Models\ActiveRecordEntity;

class Comment extends ActiveRecordEntity
{
  protected $authorId;
  protected $articleId;
  protected $text;

  public function getText()
  {
    return $this->text;
  }

  public function getArticleId()
  {
    return $this->articleId;
  }

  public function setText(string $text)
  {
    $this->text = $text;
  }

  public function setAuthor(User $author)
  {
    $this->authorId = $author->id;
  }

  public function setArticleId($articleId)
  {
    $this->articleId = $articleId;
  }

  protected static function getTableName(): string
  {
    return 'comments';
  }

  public function getAuthor(): User
  {
    return User::getById($this->authorId);
  }
}


?>