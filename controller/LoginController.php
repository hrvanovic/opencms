<?php

    namespace controller;

    use logs\SystemLog;
    use \model\usermanager\LoginUser;
    use \model\usermanager\CreateSession;

    global $core;
    $core->requireMainController();

    echo time();
    class LoginController extends Controller
    {
        public function __construct()
        {
            global $core;
            parent::__construct();

            if ($this->isLogged() == 1)
                $core->redirectUser("index");
        }

        function index()
        {
            global $core;

            $this->data = array(
                "pageInfo"   => array(
                    "title"          => "Prijava",
                    "desc"           => "Molimo da se prijavite
                ",
                    "author"         => null,
                    "keywords"       => null,
                    "featured-image" => ""
                ),
                "actionForm" => ROOT_APP . "login/password/",
                "snackbar"   => $this->snackbar,
                "links"      => array("style", "font/roboto", "material", "icons/material", "login"),
                "scripts"    => array("jquery", "material", "app", "ajax-preloader", "login")
            );

            $core->pageRender("Login/login-email", $this->data);
        }

        function password($email = null)
        {
            global $core;

            // sleep(5000);
            if ((isset($core->getParams()["urlParams"][0]) && !empty($core->getParams()["urlParams"][0]))) {
                $email = $core->getParams()["urlParams"][0];
            } else if (isset($_GET["username"]) && !empty($_GET["username"])) {
                $email = $_GET["username"];
            }

            if ((isset($core->getParams()["urlParams"][1]) && ($core->getParams()["urlParams"][1] == "notvalid"))) {
                $this->snackbar = "Vasi podaci nisu validni!";
            }

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->data = array(
                    "header"     => null,
                    "removeBody" => false,
                    "pageInfo"   => array(
                        "title"          => "IndexPage",
                        "desc"           => "Welcome",
                        "author"         => null,
                        "keywords"       => null,
                        "featured-image" => "",
                    ),
                    "actionForm" => ROOT_APP . "login/loginuser/",
                    "email"      => $email,
                    "snackbar"   => $this->snackbar,
                    "links"      => array("style", "font/roboto", "material", "icons/material", "login"),
                    "scripts"    => array("jquery", "material", "app", "ajax-preloader", "login")
                );

                $core->pageRender("Login/login-password", $this->data);

            } else {
                $this->snackbar = "Vas E-Mail nije validan!";
                $this->index();
            }
        }

        function loginUser()
        {
            global $core;

            if (isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"] && $_POST["password"])) {
                $core->requireModel("usermanager/LoginUser");

                $login = new LoginUser($_POST["username"], $_POST["password"], $core->getDB());

                if ($login->isCredValid()) {
                    $core->requireModel("usermanager/CreateSession");
                    new CreateSession($login->getUserID(), $core->getDB());
                    $core->redirectUser("index");
                } else $core->redirectUser("login/password/" . $_POST["username"] . "/notvalid");

            } else $core->redirectUser("login/index");
        }
    }