<?php
/**
 * A singleton class to store database handle.
 */
    class DB
    {
        private $conn;
        private function __construct($servername, $dbname, $user, $pass)
        {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        public static function getInstance()
        {
            if (isset(self::$conn))
            {
                self::$conn = new DB("localhost", "javra123_bugtracker", "javra", "javra@123");
            }
            return self::$conn;
        }
    }
?>