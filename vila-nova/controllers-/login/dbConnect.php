<?php

include_once '../../sys/Config.php';

class dbConnect {

    private $conn;

    function __construct() {        
    
	}

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        
        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check for database connection error
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        // returing connection resource
        return $this->conn;
    }

}

?>
