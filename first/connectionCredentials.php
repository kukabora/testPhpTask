<?php
class ConnectionCredentials
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
