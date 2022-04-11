<?php
    class Database {
        private $serverName = 'localhost';
        private $username = 'root';
        private $password = '';
        private $dbName = 'api_test';
        private $conn;

        function __construct(){
            $this->connectDB();
        }

        public function connectDB() {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->serverName . "; dbname=" . $this->dbName,
                    $this->username,
                    $this->password
                );
                // set the PDO error mode to exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Failed to connect: " . $e->getMessage();
            }

            return $this->conn;
        }

        public function closeDB() {
            $this->conn = null;
        }
        
    }
?>