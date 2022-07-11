<?php

// Контроллер главной (Main) страницы
namespace MyProject\Controllers;
use MyProject\Models\Articles\Article;
use MyProject\View\View;

class MainController
{
  private $view;

  public function __construct()
  {
    $this->view = new View(__DIR__.'/../../../templates');
  }

  public function main()
  {
    // пропала зависимость от бд
    $articles = Article::findAll();
    $this->view->renderHtml('main/main.php',['articles'=>$articles]);
  }

}

