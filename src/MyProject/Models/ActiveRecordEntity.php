<?php

namespace MyProject\Models;
use MyProject\Services\Db;

abstract class ActiveRecordEntity 
{
  protected $id;

  // Геттер
  public function getId(): int
  {
    return $this->id;
  }

  // __set если свойство не существует, забирает его имя и значение
  public function __set(string $name, $value)
  {
    $camelCaseName = $this->underscoreToCamelCase($name);
    $this->$camelCaseName = $value;
  }

  private function underscoreToCamelCase(string $source): string
  {
    return lcfirst(str_replace('_', '', ucwords($source, '_')));
  }

  private function camelCaseToUnderscore(string $source): string
  {
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
  }

  // Сущность сама заботится о связи с базой данных
  public static function findAll(): array
  {
    $db = Db::getInstance();
    // позднее статическое связывание - код зависит от класса, 
    // в котором вызывается, а не в котором описан
    return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
  }

  public static function getById(int $id): ?self
  {
    $db = Db::getInstance();
    $entities = $db->query(
      'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
      [':id'=>$id],
      static::class
    );

    return $entities ? $entities[0]: null;
  }

  public static function getByArticleId(int $id): array
  {
    $db = Db::getInstance();
    $entities = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE article_id = :id',
    [':id' => $id],
    static::class
    );
      
    return $entities;
  }

  abstract protected static function getTableName(): string;

  public function save(): void
  {
    $mappedProperties = $this->mapPropertiesToDbFormat();
    if ($this->id !== null) {
      $this->update($mappedProperties);
    } else {
      $this->insert($mappedProperties);
    }
  }

  // обновляем существующую запись в базе
  private function update(array $mappedProperties): void
  {
    $columnsToParams = [];
    $paramsToValues = [];
    $index = 1;
    foreach ($mappedProperties as $column=>$value) {
      $param = ':param'.$index; //:param1
      $columnsToParams[] = $column.' = '.$param; //column1 = :param1
      $paramsToValues[$param] = $value; //:param1 => value
      $index++;
    }

    // собираем запрос
    $sql = 'UPDATE '.static::getTableName().' SET '.implode(', ', $columnsToParams).' WHERE id='.$this->id;
    $db = Db::getInstance();
    $db->query($sql, $paramsToValues, static::class);
  }

  // создаём новую запись в базе
  private function insert(array $mappedProperties): void
  {
    // убираем элементы с null
    $filteredProperties = array_filter($mappedProperties);

    $columns = [];
    $paramsNames = [];
    $paramsToValues = [];
    foreach ($filteredProperties as $columnName=>$value) {
      $columns[] = '`'.$columnName.'`';
      $paramName = ':'.$columnName;
      $paramsNames[] = $paramName; 
      $paramsToValues[$paramName] = $value;
    }

    // создаём перечисление через запятую
    $columnsViaSemicolon = implode(', ', $columns);
    $paramsNamesViaSemicolon = implode(', ', $paramsNames);

    // собираем запрос
    $sql = 'INSERT INTO `'.static::getTableName().'` ('.$columnsViaSemicolon.') VALUES ('.$paramsNamesViaSemicolon.');'; 
    $db = Db::getInstance();
    $db->query($sql, $paramsToValues, static::class);

    $this->id = $db->getLastInsertId();
  }


  public function delete(): void
  {
    $db = Db::getInstance();
    $db->query(
      'DELETE FROM `'.static::getTableName().' WHERE id = :id',
      [':id'=>$this->id]
    );
    $this->id = null;
  }

  // рефлексия
  private function mapPropertiesToDbFormat(): array
  {
    $reflector = new \ReflectionObject($this);
    $properties = $reflector->getProperties();
    $mappedProperties = [];
    foreach ($properties as $property) {
      $propertyName = $property->getName();
      $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
      $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
    }
    return $mappedProperties;
  }
}  

?>