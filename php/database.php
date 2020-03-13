<?php
function h($string) {
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function connect() {
  $dsn = "mysql:host = localhost;dbname=sarugan2_board;charset=utf8mb4;";
  $username = "sarugan2_board";
  $password = "root";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ];
  $pdo = new PDO($dsn, $username, $password, $options);
  return $pdo;
}