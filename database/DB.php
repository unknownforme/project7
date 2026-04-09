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



    public function getAllPrisoners($name = null) {
        $query = "SELECT * FROM inmate_history 
            INNER JOIN inmate ON inmate_history.inmate_id = inmate.id
            INNER JOIN cell ON inmate_history.cell_id = cell.id
            WHERE inmate_history.time_to_release > FROM_UNIXTIME(" . time() . ");";
        if (isset($name)) {
            $query .= "WHERE name = :name";
        }
        $query = $this->dbconn->prepare($query);
        if (isset($name)) {
            $query->bindParam(":name", $name);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPrisoner($name, $cell, $reason, $time_jailed, $time_to_release, $bsn, $nationality, $gender, $length, $date_of_birth) {
        $query = $this->dbconn->prepare("INSERT INTO inmate (name, reason, bsn-number, nationality, gender, length_cm, date_of_birth) 
                        VALUES (:name, :reason, :bsn-number, :nationality, :gender, :length, :gender, :date_of_birth)
                        ON DUPLICATE KEY UPDATE id = id"); //prevents double bsn numbers
        $query->bindParam(":name", $name);
        $query->bindParam(":reason", $reason);
        $query->bindParam(":bsn-number", $bsn);
        $query->bindParam(":nationality", $nationality);
        $query->bindParam(":gender", $gender);
        $query->bindParam(":length", $length);
        $query->bindParam(":date_of_birth", $date_of_birth);
        $query->execute();
        
        self::addPrisonerHistory($cell, $reason, $time_jailed, $time_to_release);
    }

    public function getHistory() {
        $query = $this->dbconn->prepare("SELECT * FROM inmate_history 
            INNER JOIN inmate ON inmate_history.inmate_id = inmate.id
            INNER JOIN cell ON inmate_history.cell_id = cell.id");
        $query->execute();
    }

    public function addPrisonerHistory($cell, $reason, $time_jailed, $time_to_release) {
        $query = $this->dbconn->prepare("INSERT INTO inmate_history (cell_id, reason, time_jailed, time_to_release) 
                        VALUES (:cell_id, :reason, :time_jailed, :time_to_release)");
            $query->bindParam(":cell_id", $cell);
            $query->bindParam(":reason", $reason);
            $query->bindParam(":time_jailed", $time_jailed);
            $query->bindParam(":time_to_release", $time_to_release);
            $query->execute();
    }

    public function cipier_checkup($cell, $checkup_info, $date) {
        $query = $this->dbconn->prepare("INSERT INTO cipier_checkup (date, checkup_info, cell_id) VALUES (:date, :checkup_info, :cell_id)");
        $query->bindParam(":date", $date);
        $query->bindParam(":checkup_info", $checkup_info);
        $query->bindParam(":cell_id", $cell);
        $query->execute();
    }

    public function getDbconn() {
        return $this->dbconn;
    }

    public function getAllUsers() {
        $query = $this->dbconn->prepare("SELECT id, username FROM users");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($id) {
        $query = $this->dbconn->prepare("SELECT id, username FROM users WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}