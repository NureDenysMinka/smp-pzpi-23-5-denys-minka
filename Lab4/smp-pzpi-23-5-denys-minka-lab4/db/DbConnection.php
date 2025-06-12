<?php

class DbConnection
{
  private static ?DbConnection $instance = null;
  private PDO $connection;

  private function __construct()
  {
    $this->connection = new PDO('sqlite:./db/db.sqlite');
    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
  }

  public static function getInstance(): DbConnection
  {
    if (self::$instance === null) {
      self::$instance = new DbConnection();
    }

    return self::$instance;
  }

  public function getConnection(): PDO
  {
    return $this->connection;
  }
}
