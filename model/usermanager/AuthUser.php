<?php

namespace model\usermanager;

use Exception;
class AuthUser {
    private bool $isAuth = false;
    public function __construct() {
        $this->auth();
    }

    private function auth() {
        global $core;
        if(isset($_COOKIE["pik_user_auth"])) {
            try {
                $query = $core->getDB()->prepare("SELECT * FROM users_ses WHERE s_key = ?",
                    array($_COOKIE["pik_user_auth"])
                );
                $query->execute();
                if($query->rowCount() == 1) {
                    foreach ($query as $row) {
                        if($row["s_expired"] > time() && $row["s_status"] == 1) {
                            $this->isAuth = true;
                        }
                        break;
                    }
                }
            } catch (Exception $exception) {
                echo $exception;
            }
        }
    }

    public function isLogged() : bool {
        return $this->isAuth;
    }
}