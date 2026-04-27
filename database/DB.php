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

    public function getAllPrisoners($name = null, $arrest_status = "arrested") {
        $query = "SELECT * FROM inmate_history 
            INNER JOIN inmate ON inmate_history.inmate_id = inmate.id
            INNER JOIN cell ON inmate_history.cell_id = cell.id
            WHERE inmate_history.currently_jailed = 1 ";
        if ($arrest_status == "free") {
            $query .= " ";
        }
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

    public function addPrisoner($name, $bsn, $nationality, $gender, $length, $date_of_birth) {
        $query = $this->dbconn->prepare("INSERT INTO inmate (name, bsn_number, nationality, gender, length_cm, date_of_birth) 
                        VALUES (:name, :bsn_number, :nationality, :gender, :total_length, FROM_UNIXTIME(:date_of_birth))
                        ON DUPLICATE KEY UPDATE id = id"); //prevents double bsn numbers
        $query->bindParam(":name", $name);
        $query->bindParam(":bsn_number", $bsn);
        $query->bindParam(":nationality", $nationality);
        $query->bindParam(":gender", $gender);
        $query->bindParam(":total_length", $length);
        $query->bindParam(":date_of_birth", $date_of_birth);
        $query->execute();
}

    public function bsnExists($bsn) {
        $query = $this->dbconn->prepare("SELECT id from inmate WHERE bsn-number = :bsn");
        $query->bindParam(":bsn", $bsn);
        $bsn_id = $query->fetch(PDO::FETCH_ASSOC);
        if (is_int($bsn_id['id'])) {
            return true;
        }
        return false;
    }  

    public function getHistory($search = null) {
        $query = "SELECT * FROM inmate_history 
            INNER JOIN inmate ON inmate_history.inmate_id = inmate.id
            INNER JOIN cell ON inmate_history.cell_id = cell.id";
        if (isset($search)) {
            $query .= " WHERE name LIKE CONCAT('%',:name, '%')";
        }
        $query = $this->dbconn->prepare($query);
        if (isset($search)) {
            $query->bindParam(":name", $search);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCells($available = false) {
        $query = "SELECT * FROM cell";
        if ($available) {
            $query .= " WHERE in_use = 0";
        }
        $query = $this->dbconn->prepare($query);

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getCellsAndPrisoners($wing = null) {
        $query = "
            SELECT inmate_id, cell_id, cell.id, in_use, name
            FROM inmate_history
            JOIN inmate on inmate_history.cell_id = inmate.id
            RIGHT JOIN cell ON inmate_history.cell_id = cell.id";
        if (isset($wing)) {
            $query .= " WHERE vleugel = :wing";
        }
        $query = $this->dbconn->prepare($query);
        if (isset($wing)) { 
            $query->bindParam(":wing", $wing);
        }
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function occupyCell($id) {
        $id = intval($id);
        $query = $this->dbconn->prepare("UPDATE cell
            SET in_use = 1
            WHERE id = :id; ");
        $query->bindParam(":id", $id);
        $query->execute();
    }

    public function freeCell($id) {
        $query = $this->dbconn->prepare("UPDATE cell
            SET in_use = 0
            WHERE id = :id; ");
        $query->bindParam(":id", intval($id));
        $query->execute();
    }

    public function addPrisonerHistory($bsn, $cell, $reason, $time_jailed, $time_to_release) {
        $cell = intval($cell);
        $id = self::getIdByBsn($bsn);
        $query = $this->dbconn->prepare("INSERT INTO inmate_history (inmate_id, cell_id, reason, time_jailed, time_to_release) 
                        VALUES (:inmate_id, :cell_id, :reason, :time_jailed, :time_to_release)");
            $query->bindParam(":inmate_id", $id);
            $query->bindParam(":cell_id", $cell);
            $query->bindParam(":reason", $reason);
            $query->bindParam(":time_jailed", $time_jailed, PDO::PARAM_INT);
            $query->bindParam(":time_to_release", $time_to_release, PDO::PARAM_INT);
            $query->execute();
        self::occupyCell($cell);
    }

    public function getIdByBsn($bsn) {
        $bsn = intval($bsn);
        $query = $this->dbconn->prepare("SELECT id from inmate WHERE bsn_number = :bsn");
        $query->bindParam(":bsn", $bsn, PDO::PARAM_INT);
        $query->execute();
        $info = $query->fetch(PDO::FETCH_ASSOC);
        
        return $info['id'];
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