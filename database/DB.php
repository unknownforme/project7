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

    public function getprisoner($inmate_id) {
        $query = $this->dbconn->prepare("SELECT * FROM inmate 
            WHERE id = :inmate_id ");
        $query->bindParam(":inmate_id", $inmate_id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getPrisonerArrests($inmate_id) {
        $query = $this->dbconn->prepare("SELECT * FROM inmate_history 
            INNER JOIN cell ON inmate_history.cell_id = cell.id
            WHERE inmate_history.inmate_id = :inmate_id ");
        $query->bindParam(":inmate_id", $inmate_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPrisoners($name = null, $arrest_status = "free") {
        if ($arrest_status == "free") {
            $query = "SELECT * FROM inmate ";
        } else {
            $query = "SELECT * FROM inmate_history 
            INNER JOIN inmate ON inmate_history.inmate_id = inmate.id
            INNER JOIN cell ON inmate_history.cell_id = cell.id
            WHERE inmate_history.currently_jailed = 1 ";
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
        $query = $this->dbconn->prepare("INSERT INTO inmate (name, bsn, nationality, gender, length, date_of_birth) 
                        VALUES (:name, :bsn, :nationality, :gender, :total_length, FROM_UNIXTIME(:date_of_birth))
                        ON DUPLICATE KEY UPDATE id = id"); //prevents double bsn numbers
        $query->bindParam(":name", $name);
        $query->bindParam(":bsn", $bsn);
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
        $query .= " ORDER BY vleugel, cell_nr";
        $query = $this->dbconn->prepare($query);

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getCellsAndPrisoners($wing = null) {
        $query = "
            SELECT inmate_id, cell_id, cell.id, in_use, name
            FROM inmate_history
            JOIN inmate on inmate_history.cell_id = inmate.id
            RIGHT JOIN cell ON inmate_history.cell_id = cell.id ";
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

    public function updatePrisoner($id, $name, $bsn, $nationality, $gender, $length, $date_of_birth) {
        $date_of_birth = intval($date_of_birth);
        $query = $this->dbconn->prepare("UPDATE inmate
            SET name = :name, bsn = :bsn, nationality = :nationality, gender = :gender, length = :length, 
            date_of_birth = :date_of_birth 
            WHERE id = :id");
            //name, bsn, nationality, gender, length, date_of_birth) 
            //VALUES (:name, :bsn, :nationality, :gender, :total_length, FROM_UNIXTIME(:date_of_birth))
        $query->bindParam(":id", $id);
        $query->bindParam(":name", $name);
        $query->bindParam(":bsn", $bsn);
        $query->bindParam(":nationality", $nationality);
        $query->bindParam(":gender", $gender);
        $query->bindParam(":length", $length);
        $query->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_INT);
        $query->execute();
    }

    public function isCurrentlyJailed($id) {
        $query = $this->dbconn->prepare("SELECT cell_id from inmate_history WHERE inmate_id = :inmate_id AND currently_jailed = 1");
        $query->bindParam(":inmate_id", $id);
        $query->execute();
        $cell_id = $query->fetch();
        if (!empty($cell_id)) {
            return true;
        }
        return false;
    }

    public function getCellByPrisonerId($id) {
        $query = $this->dbconn->prepare("SELECT cell_id from inmate_history WHERE inmate_id = :inmate_id AND currently_jailed = 1");
        $query->bindParam(":inmate_id", $id);
        $query->execute();
        $cell_id = $query->fetch();
        if (empty($cell_id)){
            return null;
        }
        $cell_id = $cell_id['cell_id'];
        return $cell_id;
    }

    public function getPrisonerNameById($id) {
        $query = $this->dbconn->prepare("SELECT name from inmate WHERE id = :inmate_id");
        $query->bindParam(":inmate_id", $id, PDO::PARAM_INT);
        $query->execute();
        $cell_id = $query->fetch();
        $cell_id = $cell_id['name'];
        return $cell_id;
    }

    public function addRequest($type, $prisoner_id, $to = null) {
        $partone = "";
        $parttwo = "";
        if (!empty($to)) {
            $partone = ", `to`";
            $parttwo = ", :to";
        }
        $query = " INSERT INTO cipier_requests (`type`, prisoner_id $partone ) 
                    VALUES (:type, :prisoner_id $parttwo )";
        
        $query = $this->dbconn->prepare($query);
        $query->bindParam(":type", $type, PDO::PARAM_INT);
        $query->bindParam(":prisoner_id", $prisoner_id, PDO::PARAM_INT);
        if (!empty($to)) {
            $query->bindParam(":to", $to, PDO::PARAM_INT);
        }
        $query->execute();
    }

    public function notesToDo() {
        //get all ppl in jail their id's
        $query = $this->dbconn->prepare("SELECT inmate_id from inmate_history WHERE currently_jailed = 1");
        $query->execute();
        $list = $query->fetchAll(PDO::FETCH_ASSOC);
        var_dump($list);
        //check if they got a note for today, and if they dont return them as todo
    }

    public function addNote($time, $info, $prisoner_id) {
        $query = $this->dbconn->prepare("INSERT INTO cipier_checkup (`date`, checkup_info, prisoner_id) 
                VALUES (:date, :checkup_info, :prisoner_id)");
        $query->bindParam(":date", $time, PDO::PARAM_INT);
        $query->bindParam(":checkup_info", $info);
        $query->bindParam(":prisoner_id", $prisoner_id, PDO::PARAM_INT);
        $query->execute();
    }

    public function getAllNotes() {
        $query = $this->dbconn->prepare("SELECT * from cipier_checkup");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAllRequestsByPrisonerId($id) {
        $query = $this->dbconn->prepare("DELETE FROM cipier_requests WHERE prisoner_id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function getAllRequests() {
        $query = $this->dbconn->prepare("SELECT * from cipier_requests");
        $query->execute();
        return $query->fetchAll();
    }

    public function fullfillRequest($id) {
        $query = $this->dbconn->prepare("SELECT * from cipier_requests WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $info = $query->fetch();
        if ($info['type'] == 0) {
            //move prisoner
            $this->movePrisoner($info['prisoner_id'], $info['to']);
        } else {
            $this->freePrisoner($info['prisoner_id']);
        }
        $this->deleteAllRequestsByPrisonerId($id);
    }

    public function deleteRequest($id) {
        $query = $this->dbconn->prepare("DELETE FROM cipier_requests WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function freePrisoner($id) {
        $this->freeCell($this->getCellByPrisonerId($id));
        $query = $this->dbconn->prepare("UPDATE inmate_history
            SET currently_jailed = 0 WHERE inmate_id = :id");
        $query->bindParam(":id", $id);
        $query->execute();
        $this->deleteAllRequestsByPrisonerId($id);
    }

    public function movePrisoner($prisoner_id, $cell_id) {
        $this->freeCell($this->getCellByPrisonerId($prisoner_id));
        $this->occupyCell($cell_id);
        $query = $this->dbconn->prepare("UPDATE inmate_history
            SET cell_id = :cell_id WHERE inmate_id = :id AND currently_jailed = 1");
        $query->bindParam(":cell_id", $cell_id);
        $query->bindParam(":id", $prisoner_id);
        $query->execute();
        $this->deleteAllRequestsByPrisonerId($prisoner_id);
    }

    public function occupyCell($id) {
        $id = intval($id);
        $query = $this->dbconn->prepare("UPDATE cell
            SET in_use = 1
            WHERE id = :id; ");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function freeCell($id) {
        $id = intval($id);
        $query = $this->dbconn->prepare("UPDATE cell
            SET in_use = 0
            WHERE id = :id ");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function addPrisonerHistory($bsn, $cell, $reason, $time_jailed, $time_to_release) {
        $cell = intval($cell);
        $id = $this->getIdByBsn($bsn);
        $query = $this->dbconn->prepare("INSERT INTO inmate_history (inmate_id, cell_id, reason, time_jailed, time_to_release) 
                        VALUES (:inmate_id, :cell_id, :reason, :time_jailed, :time_to_release)");
            $query->bindParam(":inmate_id", $id);
            $query->bindParam(":cell_id", $cell);
            $query->bindParam(":reason", $reason);
            $query->bindParam(":time_jailed", $time_jailed, PDO::PARAM_INT);
            $query->bindParam(":time_to_release", $time_to_release, PDO::PARAM_INT);
            $query->execute();
        $this->occupyCell($cell);
    }

    public function getIdByBsn($bsn) {
        $bsn = intval($bsn);
        $query = $this->dbconn->prepare("SELECT id from inmate WHERE bsn = :bsn");
        $query->bindParam(":bsn", $bsn, PDO::PARAM_INT);
        $query->execute();
        $info = $query->fetch(PDO::FETCH_ASSOC);
        return $info['id'];
    }

    public function isPersonInJail($bsn) {
        $id = $this->getIdByBsn($bsn);
        $query = $this->dbconn->prepare("SELECT * FROM inmate_history WHERE inmate_id = :id AND currently_jailed = 1");
        $query->bindParam(":id", $id);
        $query->execute();
        $thing = $query->fetchAll(PDO::FETCH_ASSOC);
        if (empty($thing)) {
            return false;
        }
        return true;
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