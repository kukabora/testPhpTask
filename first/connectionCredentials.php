<?php
class ConnectionCredentials
/**
 * Класс для хранения данных от сервера с БД.
 * 
 * Простенький класс который используется в качестве хранилища
 * конфигов от локального сервера MySQL.
 */
{
    public $servername;
    public $username;
    public $password;
    public $database;

    public function __construct()
    {
        $this->servername = "localhost";
        $this->username = "ai";
        $this->password = "46452020";
        $this->database = "testPhp";
    }
}
