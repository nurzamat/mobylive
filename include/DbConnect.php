<?php

class DbConnect {

    private $conn;

    function __construct() {        
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once dirname(__FILE__) . '/Config.php';

        $this->conn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
        mysql_select_db(DB_NAME) or die(mysql_error());

        mysql_query('SET NAMES utf8');
        mysql_query('SET CHARACTER SET utf8' );
        mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"' );

        // returing connection resource
        return $this->conn;
    }
}

?>
