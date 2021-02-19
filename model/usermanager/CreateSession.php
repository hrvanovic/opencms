<?php

namespace model\usermanager;

class CreateSession {
    public function __construct($userid, $db) {
        $this->create($userid, $db);
    }


    private function create($userid, $db) {
        try {
            $session_key = md5($userid . time());
            $expired = time() + (86400 * 365); // 365 dana
            $agent = $_SERVER["HTTP_USER_AGENT"];

            $query = $db->prepare("INSERT INTO users_ses (s_key, s_expired, s_status, s_user, s_agent) VALUES (?, ?, ?, ?, ?)",
                array($session_key, $expired, 1, $userid, $agent));
            $query->execute();
            setcookie("pik_user_auth", $session_key, $expired, "/", URL, false);
        } catch (Exception $exception) {

        }
    }
}
