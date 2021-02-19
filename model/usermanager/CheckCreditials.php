<?php


namespace model\usermanager;
use logs\SystemLog;

class CheckCreditials {
    private $creditials_validate = false;

    public function __construct($email, $password) {
        $this->checkValidation($email, $password);
    }

    private function checkValidation($email, $password) {
        global $core;
        try {
            $query = $core->getDB()->prepare("SELECT u_id, u_password FROM users WHERE u_email = ?",
                array($email)
            );
            $query->execute();

            if($query->rowCount() == 1) {

                foreach ($query as $row) {

                    if (password_verify($password, $row["u_password"])) {
                        $this->creditials_validate = true;
                    }
                    break;
                }
            }
        } catch (Exception $e) {
            SystemLog::newLog($e, "error");
        }
    }
    public function isValid() {
        return $this->creditials_validate;
    }
}
