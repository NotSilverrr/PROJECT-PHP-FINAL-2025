<?php

namespace Core;

use PDO;

class QueryBuilder
{
  private string $sql;

  public function __construct()
  {
    $this->sql = "";
  }

  public function select(array $columns)
  {
    $this->sql = $this->sql . "SELECT " . implode(", ", $columns);

    return $this;
  }

  public function from(string $tableName)
  {
    $this->sql = $this->sql . " FROM " . $tableName;

    return $this;
  }

  public function where(string $columnName, string $columnValue)
  {
    $this->sql = $this->sql . " WHERE " . $columnName . " = " . $columnValue;

    return $this;
  }

  public function fetch()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $statement = $databaseConnection->prepare($this->sql);
    $statement->execute();
    
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function fetchAll()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $statement = $databaseConnection->prepare($this->sql);
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function execute()
  {
    $databaseConnection = new PDO(
      "mysql:host=mariadb;dbname=database",
      "user",
      "password"
    );

    $statement = $databaseConnection->prepare($this->sql);
    
    return $statement->execute();
  }

}
