<?php

class Database
{
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private mysqli $conn;

    public function __construct(string $host, string $username, string $password, string $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
    }

    private function connect()
    {
        // Create a connection to the database
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check the connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Set the character set to utf8
        $this->conn->set_charset("utf8");
    }

    public function query(string $sql)
    {
        // Perform a query on the database
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        return $result;
    }

    public function get_lats_id()
    {
        return mysqli_insert_id($this->conn);
    }

    public function close()
    {
        // Close the database connection
        $this->conn->close();
    }

    public function affected_rows()
    {
        return $this->conn->affected_rows;
    }
}
