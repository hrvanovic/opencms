<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    namespace controller;

    global $core;

    use model\usermanager\CheckCreditials;
    use model\usermanager\twofa\Twofa;
    use model\usermanager\twofa\PHPGangsta_GoogleAuthenticator;
    use model\usermanager\LogoutUser;
    use model\usermanager\UpdateUser;

    $core->requireMainController();

    class AccountController extends Controller implements ControllerInterface
    {
        public function __construct()
        {
            global $core;
            parent::__construct();

            if ($this->isLogged() == 0)
                $core->redirectUser("login");
        }

        function index($defaultTab = null)
        {
            global $core;

            $currentTab = "null";

            if($defaultTab == null) {
                if (!empty($core->getParams()["urlParams"][0])) {
                    $currentTab = $core->getParams()["urlParams"][0];
                }
            } else {
                $currentTab = $defaultTab;
            }

            $this->data = array(
                "header"      => "fixed",
                "pageInfo"    => array(
                    "title"          => "Welcome",
                    "desc"           => "Welcome",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => "",
                ),
                "user"        => $this->userInfo,
                "ajax"        => array(
                    "info"     => ROOT_APP . "account/sections/info",
                    "password" => ROOT_APP . "account/sections/password",
                    "security" => ROOT_APP . "account/info/security"
                ),
                "ajaxTabs"    => array(
                    "current"    => $currentTab,
                    "defaultURL" => ROOT_APP . "/account/sections/info",
                    "list"       => array(
                        array(
                            "id"    => "info",
                            "icon"  => "account_circle",
                            "title" => "Osobni podaci",
                            "url"   => ROOT_APP . "/account/sections/info"
                        ),
                        array(
                            "id"    => "security",
                            "icon"  => "security",
                            "title" => "Sigurnost",
                            "url"   => ROOT_APP . "/account/sections/security"
                        ),
                        array(
                            "id"    => "adjustment",
                            "icon"  => "toggle_off",
                            "title" => "Podaci i prilagodba",
                            "url"   => ROOT_APP . "/account/sections/adjustment"
                        )
                    )
                ),
                "snackbar"    => $this->snackbar,
                "links"       => array("material", "font/roboto", "style", "icons/material", "account"),
                "scripts"     => array("jquery", "material", "app", "ajax-loader", "account", "ajaxTabs")
            );

            $core->pageRender("account/index", $this->data);
        }

        function sections()
        {
            global $core;

            if (isset($core->getParams()["urlParams"][0])) {

                if ($core->getParams()["urlParams"][0] == "info") {

                    parent::updateUserInfo("contact");

                    $this->data = array(
                        "user"    => $this->userInfo
                    );

                    $core->pageRender("account/sections/info", $this->data);

                } else if ($core->getParams()["urlParams"][0] == "security") {

                    $this->section_security();

                } else if ($core->getParams()["urlParams"][0] == "adjustment") {

                    $this->data = array(
                        "formaction" => "account/updateAccountInfo/"
                    );

                    $core->pageRender("account/sections/adjustment", $this->data);
                }
            }
        }

        #[Route("/account/post/{postID}", methods: ["POST"])]
        public function post()
        {
            global $core;
            $allowed = array("2faverify", "updateName", "updateEmail", "updatePhone", "updatepassword");
            (isset($core->getParams()["urlParams"][0])) ? $postID = $core->getParams()["urlParams"][0] : $postID = null;

            if ($postID != null && in_array($core->getParams()["urlParams"][0], $allowed)) {
                switch ($postID) {
                    case "2faverify":
                        $this->twoFaEdit();
                        break;
                    case "updateName":
                        $this->postNameEdit();
                        break;
                    case "updateEmail":
                        $this->postEmailEdit();
                        break;
                    case "updatePhone":
                        $this->postPhoneEdit();
                        break;
                    case "updatepassword":
                        $this->postPasswordEdit();
                        break;
                    default:
                        $this->index("Dogodila se greska!");
                }
            }
            else
                $this->index("Dogodila se greska!");
        }

        function section_security($msg = null)
        {
            global $core;

            parent::updateUserInfo("twofa");
            parent::updateUserInfo("sessions");
            $core->requireModel("usermanager/UserAgent");

            $this->data = array(
                "formaction" => "account/updateAccountInfo/",
                "snakebar"   => $msg,
                "user" => $this->userInfo
            );
            $core->pageRender("account/sections/security", $this->data);
        }

        function editname()
        {
            global $core;

            $this->data = array(
                "header"   => "fixed-back",
                "pageInfo" => array(
                    "title"          => "Uredi Ime i prezime",
                    "desc"           => "Uredi Ime i Prezime",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => ""
                ),
                "snackbar" => $this->snackbar,
                "backURL" => ROOT_APP . "account/index/info",
                "user" => $this->userInfo,
                "links"    => array("material", "font/roboto", "account", "style", "icons/material"),
                "scripts"  => array("jquery", "material", "app", "ajax-loader", "account")
            );

            $core->pageRender("account/editname", $this->data);
        }

        function editphone()
        {
            global $core;

            $this->data = array(
                "header"   => "fixed-back",
                "pageInfo" => array(
                    "title"          => "Uredi Broj Telefona",
                    "desc"           => "Uredi Broj Telefona",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => ""
                ),
                "snackbar"    => $this->snackbar,
                "backURL" => ROOT_APP . "account/index/info",
                "user" => $this->userInfo,
                "links"    => array("material", "font/roboto", "account", "style", "icons/material"),
                "scripts"  => array("jquery", "material", "app", "account")
            );

            $core->pageRender("account/editphone", $this->data);
        }

        public function editpassword()
        {
            global $core;

            $this->data = array(
                "header"   => "fixed-back",
                "pageInfo" => array(
                    "title"          => "Uredi Lozinku",
                    "desc"           => "Uredi Lozinku",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => ""
                ),
                "snackbar"    => $this->snackbar,
                "backURL" => ROOT_APP . "account/index/security",
                "user" => $this->userInfo,
                "links"    => array("material", "font/roboto", "account", "style", "icons/material"),
                "scripts"  => array("jquery", "material", "app", "account")
            );

            $core->pageRender("account/editpassword", $this->data);
        }


        public function editemail() {
            global $core;

            $this->data = array(
                "header"   => "fixed-back",
                "pageInfo" => array(
                    "title"          => "Uredi E-Mail ",
                    "desc"           => "Uredi E-Mail",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => ""
                ),
                "snackbar"    => $this->snackbar,
                "backURL" => ROOT_APP . "account/index/info",
                "user" => $this->userInfo,
                "links"    => array("material", "font/roboto", "account", "style", "icons/material"),
                "scripts"  => array("jquery", "material", "app", "account")
            );

            $core->pageRender("account/editemail", $this->data);
        }
        public function twofa()
        {
            global $core;

            parent::updateUserInfo("2fa");

            $core->requireModel("usermanager/twofa/GoogleAuthenticator");

            $this->data = array(
                "formAction" => "/opencms/account/twofa/create",
                "header"     => "fixed-back",
                "pageInfo"   => array(
                    "title"          => "Potvrda u 2 koraka",
                    "desc"           => "",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => "",
                ),
                "backURL" => ROOT_APP . "account/index/security",
                "user"       => $this->userInfo,
                "links"      => array("material", "font/roboto", "style", "icons/material", "account", "login"),
                "scripts"    => array("jquery", "material", "app", "account")
            );

            $allowed = array("create");
            if (isset($core->getParams()["urlParams"][0]) && in_array($core->getParams()["urlParams"][0], $allowed)) {

                if (isset($_POST["password"]) && (!empty($_POST["password"]))) {
                    $core->requireModel("usermanager/CheckCreditials");

                    $CheckCreditials = new CheckCreditials($this->userInfo["basic"]["u_email"], $_POST["password"]);
                    if ($CheckCreditials->isValid()) {
                        $this->twofa_create();
                    } else {
                        $core->pageRender("globalTemplates/verify-auth", $this->data);
                    }
                } else {
                    $core->pageRender("globalTemplates/verify-auth", $this->data);
                }
            } else {
                $core->pageRender("account/twofa", $this->data);
            }
        }

        private function twofa_create() : void
        {
            global $core;
            parent::updateUserInfo("2fa");

            $core->requireModel("usermanager/twofa/Twofa");
            $Twofa = new Twofa($this->userInfo["basic"]["u_id"]);

            if($Twofa->isAuthVerified()) {
                $core->redirectUser("account/index");
                exit;
            }

            $core->requireModel("usermanager/twofa/GoogleAuthenticator");
            $core->requireModel("usermanager/twofa/Twofa");
            $GoogleAuth = new PHPGangsta_GoogleAuthenticator;
            $twoFaCode = $GoogleAuth->createSecret();
            $recoveryCodes = array();

            for ($i = 0; $i < 6; $i++) {
                $randomRecoveryCode = "a";
                array_push($recoveryCodes, $randomRecoveryCode);
            }

            $code = null;
            $recoveryCodesToString = null;
            foreach ($recoveryCodes as $code) {
                if (is_null($recoveryCodesToString))
                    $recoveryCodesToString = $code;
                else
                    $recoveryCodesToString = $code . "," . $recoveryCodesToString;
            }

            $Twofa = new Twofa($this->userInfo["basic"]["u_id"]);
            if ($Twofa->createCode($twoFaCode, $recoveryCodesToString)) {
                $this->data = array(
                    "header"     => "fixed-back",
                    "pageInfo"   => array(
                        "title"          => "Potvrda u 2 koraka",
                        "desc"           => "",
                        "author"         => null,
                        "keywords"       => null,
                        "featured-image" => "",
                    ),
                    "backurl" => ROOT_APP . "account/index/security",
                    "post"       => ROOT_APP . "account/post/2faverify",
                    "twofa"      => array(
                        "code"     => $twoFaCode,
                        "recovery" => $recoveryCodes,
                    ),
                    "twofa_apps" => array(
                        array("title" => "Google Authenticator", "url" => "https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"),
                        array("title" => "Microsoft Authenticator", "url" => "https://play.google.com/store/apps/details?id=com.azure.authenticator"),
                        array("title" => "Authy", "url" => "https://play.google.com/store/apps/details?id=com.authy.authy")
                    ),
                    "user"       => $this->userInfo,
                    "links"      => array("material", "font/roboto", "style", "icons/material", "account", "login"),
                    "scripts"    => array("jquery", "material", "app", "account")
                );
                $core->pageRender("account/twofa-create", $this->data);
            } else
                // Forbiden
                $core->callErrorController("error_403");
        }


        function logout()
        {
            global $core;
            $core->requireModel("usermanager/LogoutUser");
            LogoutUser::logout();
        }

        /** PRIVATE FUNCTIONS */

        private function twoFaEdit() {
            global $core;

            $core->requireModel("usermanager/twofa/Twofa");
            $Twofa = new Twofa($this->userInfo["basic"]["u_id"]);
            if ($Twofa->verifyCode($_POST["code"])) {
                $core->requireModel("usermanager/UpdateUser");
                UpdateUser::twoFaStatus(true);
                $this->snackbar = "Potvrda u 2 koraka je uspjesno postavljena";
                $this->index("security");
            } else {
                $this->snackbar = "Potvrda u 2 koraka nije postavljena, kod nije tacann";
                $this->index("security");
            }
        }
        private function postEmailEdit() {
            global $core;
            $core->requireModel("usermanager/UpdateUser");
            UpdateUser::email($_POST["email"]);
            parent::updateUserInfo("basic");
            $this->snackbar = "Azurirali ste informacije!";
            $this->editemail();
        }

        private function postPhoneEdit() {
            global $core;

            $core->requireModel("usermanager/UpdateUser");
            UpdateUser::phone($_POST["phone"]);
            parent::updateUserInfo("basic");
            $this->snackbar = "Azurirali ste informacije!";
            $this->editphone();
        }

        private function postPasswordEdit() {
            global $core;

            $core->requireModel("usermanager/UpdateUser");
            UpdateUser::password($_POST["newpassword"]);
            parent::updateUserInfo("basic");
            $this->snackbar = "Azurirali ste lozinku!";
            $this->editpassword();
        }

        private function postNameEdit() {
            global $core;

            $core->requireModel("usermanager/UpdateUser");
            UpdateUser::name($_POST["firstname"], $_POST["lastname"]);
            parent::updateUserInfo("basic");
            $this->snackbar = "Azurirali ste informacije!";
            $this->editname();
        }
    }
