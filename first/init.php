<?php

/**
 * В этом файле будет описан класс, отвечающий
 * за подключение, наполнение, а так же получение
 * информации из БД (в данном случае движок - MySQL).
 * Подключение произведено через PDO коннектор.
 */
require_once("connectionCredentials.php");

/**
 * Класс Init, описанный ниже, служит для работы с реляционной базой данных.
 * 
 * Класс будет состоять из 3 (4) методов для разделения функционала
 * и упрощения читаемости кода. Класс является исключительно тестовым и
 * в настоящий продакшн его лучше не вставлять до самого момента ревью, хотя методы
 * и подходы здесь были использованны, что называется, "по-уму" (как я, сам, сугубо лично 
 * считаю).
 * 
 */
final class Init
{
  /** @type object|null Переменная для сохранения соединения */
  private $conn;
  /** @type array| Возможные значения для заполнения таблицы */
  private $possibleValues = array('normal', 'success', 'failed', 'non-responded');
  /** @type array| Возможные значения для заполнения таблицы */
  private $possibleKeys = array('APIquery', 'DBquery', 'Mobile-order', '3\`d-party-library');
  /** @type string| Переменная для сохранения данных для инициализации подключения (название сервера) */
  private $servername;
  /** @type string| Переменная для сохранения данных для инициализации подключения (имя пользователя)*/
  private $username;
  /** @type string| Переменная для сохранения данных для инициализации подключения (пароль пользователя) */
  private $password;
  /** @type string| Переменная для сохранения данных для инициализации подключения (название базы данных) */
  private $database;

  public function __construct()
  {
    /**
     * Конструктор основного класса
     * 
     * Конструктор, в котором подтягиваются данные с выделенного класса
     * ConnectionCredentials, в котором сохраняется вся информация для 
     * успешного подключения к БД. ConnectionCredentials в данном случае можно расценивать
     * как некий Конфиг-класс. Помимо прочего, тут же вызываются два следующих
     * метода класса, а именно connect(что подключает класс к БД и создает
     * соединение), create(который непосредственно создает табличку), и fill(
     * функцией которого является заполнение таблицы тестовыми данными).
     * 
     * @return void 
     */
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
    /**
     * Метод для создания подключения к БД.
     * 
     * В этом методе создается подключение к базы данных с посредством 
     * PDO-коннектора (он предпочтителен, так как с него легко смигрировать
     * на другой движок, скажем, Potgre). Затем коннектор сохраняется во внутреннем
     * параметре conn, а в случае ошибки отлавливается error message.
     * 
     * @return void
     */
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
    /**
     * Метод для первоначального создания тест-таблицы.
     * 
     * Создается две строки запроса. Одна для удаления таблицы, если она уже
     * существует, вторая для создания таблицы и присвоения ей различных данных.
     * Оба запроса осуществляются последовательно друг за другом. После успешного 
     * выполнения в консоль выводится сообщение.
     * 
     * @return void
     */
    $preQuery = "DROP TABLE IF EXISTS test;";
    $this->conn->query($preQuery);
    $queryStatement = "CREATE TABLE test(
      id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
      create_time DATETIME COMMENT 'Create Time' DEFAULT NOW(),
      update_time DATETIME COMMENT 'Update Time' DEFAULT NOW(),
      request VARCHAR(255) COMMENT '',
      result VARCHAR(255) COMMENT ''
    ) DEFAULT CHARSET UTF8 COMMENT '';";
    $this->conn->query($queryStatement);
    print("Table has been successfully created.");
  }

  private function fill()
  {
    /**
     * Метод для заполнения таблицы первичными данными.
     * 
     * Метод без позиционных аргументов автоматически подтягивает 
     * два массива, объявленных в начале класса, которые генерируют от 50 до 100 (не включительно)
     * строк с фиктивными данными, для заполнения таблицы и ее дальнейшей проверки. По 
     * окончанию выполнения выводится сообщение.
     * 
     * @return void
     */
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
    /**
     * Метод для получения тестовых данных из таблицы test.
     * 
     * Простой метод, выполняющий 1 SQL запрос с применением конструкции
     * IN, возвращающий все строки, чье  значение result соответствует
     * normal или success.
     * 
     * @return array
     */
    $queryStatement = "SELECT * FROM test WHERE result in ('normal', 'success');";
    return $this->conn->query($queryStatement);
  }
}
