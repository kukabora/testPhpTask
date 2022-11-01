<?php
require_once("connectionCredentials.php");
final class Init
{
  private $conn;
  private $possibleValues = array('normal', 'success', 'failed', 'non-responded');
  private $possibleKeys = array('APIquery', 'DBquery', 'Mobile-order', '3\`d-party-library');
  private $servername;
  private $username;
  private $password;
  private $database;

  public function __construct()
  {
    $credentials = new ConnectionCredentials();
    $this->servername = $credentials->servername;
    $this->username = $credentials->username;
    $this->password = $credentials->password;
    $this->database = $credentials->database;
    $this->connect();
    $this->create();
    $this->fill();
  }

  private function connect()
  {
    try {
      $conn = new PDO("mysql:host=$this->servername;dbname=" . $this->database, $this->username, $this->password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn = $conn;
      print_r("Connected successfully");
    } catch (PDOException $e) {
      print_r("Connection failed: " . $e->getMessage());
    }
  }

  private function create()
  {
    $preQuery = "DROP TABLE IF EXISTS test;";
    $this->conn->query($preQuery);
    $queryStatement = "CREATE TABLE test(
      id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
      create_time DATETIME COMMENT 'Create Time',
      update_time DATETIME COMMENT 'Update Time',
      request VARCHAR(255) COMMENT '',
      result VARCHAR(255) COMMENT ''
    ) DEFAULT CHARSET UTF8 COMMENT '';";
    $this->conn->query($queryStatement);
    print("Table has been successfully created.");
  }

  private function fill()
  {
    $amountOfRows = rand(50, 100);
    for ($i = 0; $i < $amountOfRows; $i++) {
      $currentRequest = $this->possibleKeys[rand(0, count($this->possibleKeys) - 1)];
      $currentResult = $this->possibleValues[rand(0, count($this->possibleValues) - 1)];
      $queryStatement = "INSERT INTO test (request, result) VALUES ('$currentRequest', '$currentResult')";
      $this->conn->query($queryStatement);
    }
    print("Table has been successfully filled");
  }

  public function get()
  {
    $queryStatement = "SELECT * FROM test WHERE result in ('normal', 'success');";
    return $this->conn->query($queryStatement);
  }
}
