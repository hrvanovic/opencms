<?php

namespace model\usermanager;

use \mysqli;
use mysqli_result;
use mysqli_stmt;

class LoginUser {
    private $userid = NULL;
    private $validCred = false;

    private string $email;
    private string $password;

    public function __construct($email, $password, $db) {
        $this->email = $db->escapeString($email);
        $this->password = $password;
        $this->login($db->escapeString($email), $password, $db);
    }

    private function login($email, $password, $db) {
        try {
            $query = $db->prepare("SELECT u_id, u_password FROM users WHERE u_email = ?",
                array($email)
            );

            $query->execute();

            if($query->rowCount() == 1) {
                foreach ($query as $row) {
                    if (password_verify($password, $row["u_password"])) {
                        $this->validCred = true;
                        $this->userid = $row["u_id"];
                    }
                    break;
                }
            }

        } catch (Exception $e) {
            global $core;
            $core->SystemLog()::newLog($e, "error");
        }
    }

    public function getUserID() {
        return $this->userid;
    }

    public function isCredValid() {
        return $this->validCred;
    }
}
