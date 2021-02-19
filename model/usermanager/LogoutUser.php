<?php

namespace model\usermanager;

class LogoutUser {
    public static function logout() {
        global $core;
        if(isset($_COOKIE["pik_user_auth"])) {
            try {
                $query = $core->getDB()->prepare("UPDATE users_ses SET s_status = '0' WHERE s_key = ?",
                    array($_COOKIE["pik_user_auth"])
                );
                $query->execute();
                $core->redirectUser("login");
            } catch (Exception) {

            }
        }
    }
}
