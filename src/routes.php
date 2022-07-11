<!-- Универсальная схема роутинга -->
<?php

return [
  // Обработка приветствия
  '~^hello/(.*)$~'=>[\MyProject\Controllers\MainController::class, 'sayHello'],
  // Обработка пустой строки
  '~^$~'=>[\MyProject\Controllers\MainController::class, 'main'],
  // Прощание
  '~^bye/(.*)$~'=>[\MyProject\Controllers\MainController::class, 'sayGoodbye'],

  // Страница с просмотром статьи
  '~^articles/(\d+)$~'=>[\MyProject\Controllers\ArticlesController::class, 'view'],
  // Страница редактирования статьи
  '~^articles/(\d+)/edit$~'=>[\MyProject\Controllers\ArticlesController::class, 'edit'],
  // Страница создания статьи
  '~^articles/add$~'=>[\MyProject\Controllers\ArticlesController::class, 'add'],
  // Страница комментареив к статье
  '~^articles/(\d+)/comments$~' => [MyProject\Controllers\CommentController::class, 'add'],
  // Страница редактирования комментария
  '~^comments/(\d+)/edit$~' => [MyProject\Controllers\CommentController::class, 'edit'],

    
];

?>