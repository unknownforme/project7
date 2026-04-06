<?php

class DB {

    protected $dbconn;

    public function __construct() {
        $this->dbconn = new PDO(
            'mysql:dbname=gevangenis;host=127.0.0.1;port=3306;',
            'root',
            '',
        );
    }

    public function addPrisoner($name, $department, $cell) {
        $query = $this->dbconn->prepare("INSERT INTO ");
    }

    public function getDbconn() {
        return $this->dbconn;
    }
}