<?php

namespace MyProject\Services;

class Db 
{
// для работы с базой данных
private $pdo;
private static $instance;

private function __construct() 
  {

  $dbOptions = (require __DIR__.'/../../settings.php')['db'];

  $this->pdo = new \PDO (
    'mysql:host='.$dbOptions['host'].';dbname='.$dbOptions['dbname'],
    $dbOptions['user'],
    $dbOptions['password']
  );

  // Выполняет запрос и возвращает кол-во затронутых строк
  $this->pdo->exec('SET NAMES UTF8');
  }

  // stdClass - встроенный класс без свойств и методов
  public function query(string $sql, $params=[], string $className='stdClass'): ?array 
  {
    // подготавливает запрос и возвращает объект
    $sth = $this->pdo->prepare($sql);
    $result = $sth->execute($params);

    if (false === $result) {
      return null;
    }
    // выбирает все данные из результата запроса и помещает их в массив
    return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
  }

  public static function getInstance(): self
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function getLastInsertId(): int
  {
    return $this->pdo->lastInsertId();
  }

}

?>