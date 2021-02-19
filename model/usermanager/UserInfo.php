<?php

    namespace model\usermanager;
    use Exception;

    class UserInfo
    {
        public static function get2faInfo(int $userID) : array {
            global $core;
            try {
                $query = $core->getDB()->prepare("SELECT fa_code, fa_reccode, fa_status FROM users_fa WHERE fa_user = ?", array($userID));
                $query->execute();
                $fetch = $query->fetchAll();
                return $fetch[0];
            } catch (Exception $exception) {
                $core->SystemLog()->newLog($exception);
            }

            return array();
        }

        public static function getBasicInfo(string $column = "cookie", string $value = null) : array {
            global $core;
            try {
                if (($column = "cookie" && isset($_COOKIE["pik_user_auth"])))
                    $query = $core->getDB()->prepare("SELECT u_id, u_email, u_phone, u_firstname, u_lastname FROM users WHERE u_id IN (SELECT s_user FROM users_ses WHERE s_key = ?)",
                        array($_COOKIE["pik_user_auth"])
                    );
                 else
                    $query = $core->getDB()->prepare("SELECT u_id, u_email, u_phone u_firstname, u_lastname FROM users WHERE ? = ?",
                        array($column, $value)
                    );

                $query->execute();

                if ($query->rowCount() == 1) {
                    $fetch = $query->fetchAll();
                    return $fetch[0];
                }
            } catch (Exception $exception) {
                $core->SystemLog()->newLog($exception);
            }

            return array();
        }

        public static function getContactInfo(int $userID) : array {
            global $core;
            $info = array();
            try {
                $query = $core->getDB()->prepare("SELECT c_type, c_value FROM users_con WHERE c_user = ?", array($userID));
                $query->execute();
                $fetch = $query->fetchAll();
                foreach ($fetch as $contact) {
                    if ($contact["c_type"] == 0)
                        $info["contact"]["email"][] = $contact;
                    else
                        $info["contact"]["phone"][] = $contact;
                }

                return $info;
            } catch (Exception $exception) {
                $core->SystemLog()->newLog($exception);
            }

            return array();
        }
        public static function getSessions(int $userID) : array {
            global $core;
            $info = array();

            try {
                $currentTime = time();
                $query = $core->getDB()->prepare("SELECT s_user, s_agent, s_expired, s_status FROM users_ses WHERE s_user = ? AND s_expired > ?",
                array($userID, $currentTime));
                $query->execute();
                $fetch = $query->fetchAll();

                return $fetch;
            } catch (Exception) {}

            return array();
        }
    }