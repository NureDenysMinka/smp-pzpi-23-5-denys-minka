<?php

require_once 'db/DbConnection.php';

class ProductsService
{
  private readonly PDO $connection;

  public function __construct()
  {
    $this->connection = DbConnection::getInstance()->getConnection();
  }

  public function getProducts(): array {
    $stmt = $this->connection->query("select * from products");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}