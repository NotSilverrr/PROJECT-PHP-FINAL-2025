<?php

namespace Core;

use PDO;

class QueryBuilder
{
  private string $sql;
  private array $parameters;

  public function __construct()
  {
    $this->sql = "";
    $this->parameters = [];
  }

  public function select(?array $columns = null)
  {
    if ($columns === null || empty($columns)) {
      $this->sql = $this->sql . "SELECT *";
    } else {
      $this->sql = $this->sql . "SELECT " . implode(", ", $columns);
    }
    return $this;
  }

  public function delete()
  {
    $this->sql = $this->sql . "DELETE ";

    return $this;
  }

  public function update()
  {
    $this->sql = $this->sql . "UPDATE ";

    return $this;
  }

  public function set(array $columnValues)
  {
    $sets = [];
    foreach ($columnValues as $column => $value) {
      $sets[] = "$column = '$value'";
    }
    $this->sql = $this->sql . " SET " . implode(", ", $sets);

    return $this;
  }

  public function from(string $tableName)
  {
    $this->sql = $this->sql . " FROM " . $tableName;
    return $this;
  }

  public function where(string $columnName, $operator, $value)
  {
    $paramName = ":where_" . count($this->parameters);
    $this->sql = $this->sql . " WHERE " . $columnName . " " . $operator . " " . $paramName;
    $this->parameters[$paramName] = $value;
    return $this;
  }

  public function join(string $table, string $firstKey, string $operator, string $secondKey, string $type = 'INNER')
  {
    $this->sql = $this->sql . " " . $type . " JOIN " . $table . " ON " . $firstKey . " " . $operator . " " . $secondKey;
    return $this;
  }

  public function leftJoin(string $table, string $firstKey, string $operator, string $secondKey)
  {
    return $this->join($table, $firstKey, $operator, $secondKey, 'LEFT');
  }

  public function rightJoin(string $table, string $firstKey, string $operator, string $secondKey)
  {
    return $this->join($table, $firstKey, $operator, $secondKey, 'RIGHT');
  }

  public function andWhere(string $columnName, $value)
  {
    $paramName = ":where_" . count($this->parameters);
    $this->sql = $this->sql . " AND " . $columnName . " = " . $paramName;
    $this->parameters[$paramName] = $value;
    return $this;
  }

  public function orWhere(string $columnName, $value)
  {
    $paramName = ":where_" . count($this->parameters);
    $this->sql = $this->sql . " OR " . $columnName . " = " . $paramName;
    $this->parameters[$paramName] = $value;
    return $this;
  }

  public function insert()
  {
    $this->sql = $this->sql . "INSERT ";
    return $this;
  }

  public function into(string $tableName, array $columns)
  {
    $this->sql = $this->sql . "INTO " . $tableName;
    $this->sql .= " (" . implode(", ", $columns) . ")";
    return $this;
  }

  public function values(array $values)
  {
    $placeholders = [];
    foreach ($values as $key => $value) {
      $paramName = ":value_" . count($this->parameters);
      $placeholders[] = $paramName;
      $this->parameters[$paramName] = $value;
    }
    $this->sql .= " VALUES (" . implode(", ", $placeholders) . ")";
    return $this;
  }

  private function executeStatement()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $statement = $databaseConnection->prepare($this->sql);
    
    foreach ($this->parameters as $param => $value) {
      $statement->bindValue($param, $value);
    }
    
    $statement->execute();
    return $statement;
  }

  public function fetch()
  {
    $statement = $this->executeStatement();
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function fetchAll()
  {
    $statement = $this->executeStatement();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function execute()
  {
    return $this->executeStatement();
  }

  // Méthode utile pour le debug
  public function getSQL()
  {
    return [
      'query' => $this->sql,
      'parameters' => $this->parameters
    ];
  }
}