<?php

abstract class DB {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {

            try {
                self::$instance = new PDO('mysql:host=localhost;dbname=proj7703_vilanova', 'proj7703_vilanov', 'BGWbI~O+k;Uo');
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        return self::$instance;
    }

    public static function prepare($sql) {
        return self::getInstance()->prepare($sql);
    }
    
    public static function lastInsertId($name = NULL) {
		return self::getInstance()->lastInsertId($name);
	}

}
