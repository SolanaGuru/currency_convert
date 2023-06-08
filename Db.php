<?php

class Db
{
    private $host = "containers-us-west-176.railway.app"; //replace with your host name
    private $username = "root"; //replace with your username
    private $password = "hKDXgYtkz5kW2MikoWHq"; //replace with your password
    private $database = "railway"; //replace with your database name
    private $port = 6575; //replace with database server port
    
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function migrate_tables()
    {
        $sql_rate = "CREATE TABLE IF NOT EXISTS rates (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            no VARCHAR(30),
            tradingDate VARCHAR(30),
            effectiveDate VARCHAR(50),
            currency VARCHAR(50),
            code VARCHAR(50),
            bid FLOAT(50),
            ask FLOAT(50)
        )";

        $sql_convert = "CREATE TABLE IF NOT EXISTS converted (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            source_amount INT(11),
            source_currency FLOAT(30),
            target_currency FLOAT(30),
            target_amount FLOAT(30),
            source_code VARCHAR(50),
            target_code VARCHAR(50),
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $this->conn->query($sql_rate);
        $this->conn->query($sql_convert);
    }

    public function query($sql)
    {
        return $this->conn->query($sql);

    }
}
