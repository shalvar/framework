<?php

// Место где вызывается контроллер и его методы - фронт-контроллер
// require __DIR__.'../../src/MyProject/Models/Users/User.php';
// require __DIR__.'../../src/MyProject/Models/Articles/Article.php';

// Автозагрузка классов

spl_autoload_register(function (string $className)
{
  require_once __DIR__.'../../src/'.str_replace('\\','/', $className).'.php';
});

$route = $_GET['route']??'';
$routes = require __DIR__.'../../src/routes.php';

// Проходим по массиву и проверяем 
$isRouteFound = false;
foreach ($routes as $pattern => $controllerAndAction) {
  preg_match($pattern, $route, $matches);
  if (!empty($matches)) {
    $isRouteFound = true;
    break;
  }
}

if (!$isRouteFound) {
  echo 'Страница не найдена';
  return;
}

unset($matches[0]);

$controllerName = $controllerAndAction[0];
$actionName = $controllerAndAction[1];

// Переменную можно использовать в качестве имени класса при создании объекта, и даже вместо имени метода
$controller = new $controllerName();
// ... передаст элементы массива в качестве аргументов методу в том порядке, в котором они находятся в массиве.
$controller->$actionName(...$matches);


?>