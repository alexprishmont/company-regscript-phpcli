<?php

class Company {
    private $company;
    private $id;

    public function __construct() { $this->company = []; }
    public function add($data = []) { $this->company = $data; }
    public function comment($com) { $this->company[] = $com; }
    public function store() {
        try {
            $GLOBALS['database']->query("INSERT INTO `companies` (name, registration_code, email, phone, comment) VALUES (?, ?, ?, ?, ?)", $this->company);
            echo "New company stored in database!\n";
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }
    public function unique_email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Your entered email adress not valid! Try re-enter email.\n";
            return false;
        }
        try {
            $result = $GLOBALS['database']->query("SELECT `id` FROM `companies` WHERE `email` = ?", [$email]);
            if ($result->rowCount() > 0) {
                echo "Company with such email adress already exists. Try re-enter company data.\n";
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getmessage();
            return false;
        }
        return true;
    }
    public function valid_phone($phone) {
        if ((preg_match("/[^0-9]/", '', $phone)) && (strlen($phone) > 11 && stlrne($phone) < 9)) return false;
        return true;
    }
    public function find_company_by_name($name) {
        try {
            $result = $GLOBALS['database']->query("SELECT * FROM `companies` WHERE `name` = ? LIMIT 1", [$name]);
            if ($result->rowCount() > 0) {
                $fetch = $result->fetchAll();
                foreach ($fetch as $data) {
                    $this->company[] = $data['name'];
                    $this->company[] = $data['registration_code'];
                    $this->company[] = $data['email'];
                    $this->company[] = $data['phone'];
                    $this->company[] = $data['comment'];
                    $this->id = $data['id'];
                }
                return true;
            }
            else {
                echo "Company with name [".$name."] could not be find in database.\n";
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }
    public function update_company_data($data, $type) {
        try {
            $query = "UPDATE `companies` SET ".$type." = ? WHERE `id` = ?";
            $GLOBALS['database']->query($query, [$data, $this->id]);
            if ($type == "name") $this->company[0] = $data;
            echo "Company data successfully updated.\n";
            $this->find_company_by_name($this->company[0]);
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }
    public function delete_company() {
        try {
            $GLOBALS['database']->query("DELETE FROM `companies` WHERE id = ? LIMIT 1", [$this->id]);
            echo "Company [".$this->company[0]."] deleted!\n";
        } catch (PDOException $e) {
            echo $e->getmessage();
        }
    }
}
?>
