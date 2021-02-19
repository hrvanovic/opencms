<?php


    namespace model\usermanager\twofa;
    use PDO;

    class Twofa
    {
        private $db;
        private $userID;
        private $query;

        public function __construct($userID) {
            global $core;
            $this->userID = $userID;

            $query0 = $core->getDB()->prepare("SELECT fa_code, fa_status FROM users_fa WHERE fa_user = ?",
                array($userID)
            );
            $query0->execute();
            $this->query = $query0;
        }

        public function createCode(string $twoFaCode, string $recoveryCodes) : bool
        {
            global $core;
            try {
                if ($this->query->rowCount() == 0) {
                    $query1 = $core->getDB()->prepare("INSERT INTO users_fa (fa_code, fa_user, fa_reccode) VALUES (?, ?, ?)",
                        array($twoFaCode, $this->userID, $recoveryCodes));
                    $query1->execute();
                    return true;
                } else {
                    if ($core->getDB()->fetchAll($this->query)[1] == 0) {
                        $query1 = $core->getDB()->prepare("UPDATE users_fa SET fa_code = ?, fa_reccode = ? WHERE fa_user = ?",
                            array($twoFaCode, $recoveryCodes, $this->userID));
                        $query1->execute();
                        return true;

                    }
                }
                return false;
            } catch (Exception $e) {
                return false;
            }
        }

        public function isAuthVerified() : bool {
            global $core;
            return ($core->getDB()->fetchAll($this->query)[0] == 1);
        }

        public function verifyCode($code) : bool {
            global $core;
            $core->requireModel("usermanager/twofa/GoogleAuthenticator");
            $GoogleAuth = new PHPGangsta_GoogleAuthenticator();
            return $GoogleAuth->verifyCode($core->getDB()->fetchAll($this->query)[0], $code, 2);
        }
    }
