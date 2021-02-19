<?php


namespace controller;

use model\databases\PDODatabase;
use model\usermanager\AuthUser;
use model\usermanager\UserInfo;


class Controller {
    protected $data;
    protected $userInfo;
    protected $snackbar = null;
    /**
     * Controller konstruktor.
     */
    public function __construct() {
        $this->updateUserInfo("basic");
    }

    /**
     * Funkcija da provjeri da li je korisnik loginovan.
     * @return bool (true - korisnik je ulogovan, false - nije)
     */
    protected function isLogged() {
        global $core;

        $core->requireModel("usermanager/AuthUser");
        $AuthUser = new AuthUser();
        return $AuthUser->isLogged();
    }

    /**
     * Funkcija da azurira objekat 'userInfo' sa korisnickim podacima
     * koji je logovan.
     */
    protected function updateUserInfo($infoType) {
        global $core;

        $core->requireModel("usermanager/UserInfo");

        if($infoType == "basic")
            $this->userInfo["basic"] = UserInfo::getBasicInfo();
        else if($infoType == "contact")
            $this->userInfo["contact"] = UserInfo::getContactInfo($this->userInfo["basic"]["u_id"]);
        else if($infoType == "twofa")
            $this->userInfo["twofa"] = UserInfo::get2faInfo($this->userInfo["basic"]["u_id"]);
        else if($infoType == "sessions")
            $this->userInfo["sessions"] = UserInfo::getSessions($this->userInfo["basic"]["u_id"]);
    }
}

interface ControllerInterface {
    public function index();
    public function sections();
}
