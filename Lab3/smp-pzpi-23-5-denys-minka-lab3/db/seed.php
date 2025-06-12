<?php

require_once 'db/DbConnection.php';

function seed(): void
{
  $connection = DbConnection::getInstance()->getConnection();

  $connection->exec("
    create table if not exists products (
        id integer primary key autoincrement,
        name text not null,
        price integer not null
    )
  ");

  $stmt = $connection->query("select count(*) from products");
  $count = (int) $stmt->fetchColumn();

  if ($count == 0) {
    $connection->exec("
        insert into products (name, price) values
        ('Молоко пастеризоване', 12),
        ('Хліб чорний', 9),
        ('Сир білий', 21),
        ('Сметана 20%', 25),
        ('Кефір 1%', 19),
        ('Вода газована', 25),
        ('Печиво \"Весна\"', 25);
    ");
  }
}
