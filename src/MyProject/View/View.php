<?php
namespace MyProject\View;

class View 
  {
  private $templatesPath;

  public function __construct(string $templatesPath)
  {
    $this->templatesPath = $templatesPath;

  }
  // Создаём путь к шаблону
  public function renderHtml(string $templateName, array $vars=[], int $code=200)
  {
    // сообщаем браузеру код ошибки
    http_response_code($code);
    // извлекает массив в переменные
    extract($vars);
    include $this->templatesPath.'/'.$templateName;
  }
  }
?>