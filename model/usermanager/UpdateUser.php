<?php

namespace model\usermanager;
use Exception;

class UpdateUser
{
    public static function name($firstName, $lastName, string $column = "cookie", string $value = null) {
        global $core;
        try {
            if (($column = "cookie" && isset($_COOKIE["pik_user_auth"])))
                $query = $core->getDB()->prepare("UPDATE users SET u_firstname = ?, u_lastname = ? WHERE u_id IN (SELECT s_user FROM users_ses WHERE s_key = ?)",
                    array($firstName, $lastName, $_COOKIE["pik_user_auth"])
                );
            else
                $query = $core->getDB()->prepare("UPDATE users SET u_firstname = ?, u_lastname = ? WHERE ? = ?",
                    array($firstName, $lastName, $column, $value)
                );

            $query->execute();

        } catch (Exception $exception) {
            $core->SystemLog()->newLog($exception);
        }
    }

    public static function email($email, string $column = "cookie", string $value = null) {
        global $core;
        try {
            if (($column = "cookie" && isset($_COOKIE["pik_user_auth"])))
                $query = $core->getDB()->prepare("UPDATE users SET u_email = ? WHERE u_id IN (SELECT s_user FROM users_ses WHERE s_key = ?)",
                    array($email, $_COOKIE["pik_user_auth"])
                );
            else
                $query = $core->getDB()->prepare("UPDATE users SET u_email = ? WHERE ? = ?",
                    array($email, $column, $value)
                );

            $query->execute();

        } catch (Exception $exception) {
            $core->SystemLog()->newLog($exception);
        }
    }

    public static function phone($phone, string $column = "cookie", string $value = null) {
        global $core;
        try {
            if (($column = "cookie" && isset($_COOKIE["pik_user_auth"])))
                $query = $core->getDB()->prepare("UPDATE users SET u_phone = ? WHERE u_id IN (SELECT s_user FROM users_ses WHERE s_key = ?)",
                    array($phone, $_COOKIE["pik_user_auth"])
                );
            else
                $query = $core->getDB()->prepare("UPDATE users SET u_phone = ? WHERE ? = ?",
                    array($phone, $column, $value)
                );

            $query->execute();

        } catch (Exception $exception) {
            $core->SystemLog()->newLog($exception);
        }
    }

    public static function twoFaStatus($bool, string $column = "cookie", string $value = null) {
        global $core;
        try {
            if (($column = "cookie" && isset($_COOKIE["pik_user_auth"])))
                $query = $core->getDB()->prepare("UPDATE users_fa SET fa_status = ? WHERE fa_user IN (SELECT s_user FROM users_ses WHERE s_key = ?)",
                    array($bool, $_COOKIE["pik_user_auth"])
                );
            else
                $query = $core->getDB()->prepare("UPDATE users_fa SET fa_status = ? WHERE ? = ?",
                    array($bool, $column, $value)
                );

            $query->execute();

        } catch (Exception $exception) {
            $core->SystemLog()->newLog($exception);
        }
    }

    public static function password($password, string $column = "cookie", string $value = null) {
        global $core;
        try {
            $password = password_hash($password, PASSWORD_DEFAULT);
            if (($column = "cookie" && isset($_COOKIE["pik_user_auth"])))
                $query = $core->getDB()->prepare("UPDATE users SET u_password = ? WHERE u_id IN (SELECT s_user FROM users_ses WHERE s_key = ?)",
                    array($password, $_COOKIE["pik_user_auth"])
                );
            else
                $query = $core->getDB()->prepare("UPDATE users SET u_password = ? WHERE ? = ?",
                    array($password, $column, $value)
                );

            $query->execute();

        } catch (Exception $exception) {
            $core->SystemLog()->newLog($exception);
        }
    }
}