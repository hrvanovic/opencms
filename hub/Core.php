<?php

/*
 *
 * PHP version 7
 * @author     Original author <hrvanovic.dev@gmail.com>
 * @copyright  2019-2020 BlixmarkProject
 * @license    http://www.blixmark.com/license/opencms
 * @version    1.0.0
 * @package    OpenCMS
 * @subpackage Core
 * @since      File available since Release 1.0.0
 * @deprecated File deprecated in Release 1.0.0
 */

namespace hub;

use model\databases\MySQLiDatabase;
use model\usermanager\userInfo;
use model\Sitemanager\SiteInfo;
use ParseError;
use Exception;

require ROOT . "hub/CoreInterface.php";

class Core implements CoreInterface
{

    private $callErrorNum = 0;
    private $renderNum = 0;
    
    public $database;
    public $siteInfo;
    public $userInfo;

    // Page Render!
    public function __construct()
    {
        set_error_handler(array(
            $this,
            "error_handler"
        ));
        
        if(is_null($this->database)) {
            $this->requireDatabase();
            $this->database = new MySQLiDatabase;
        }
        
        if(is_null($this->siteInfo)) {
            $this->requireSiteInfo();
            $this->siteInfo = new SiteInfo($this->database);
        }
    }


    /**
     * Error handler. Funkcija kontrolise svaki error zasebno.
     * Error se zapisuje u log fajl i poziva se Error kontroler korisniku.
     * @param int $numberOfErrors Broj Greske
     * @param string $string Opis Greske
     * @param file $file Fajl u kojem se greska nalazi
     * @param int $onLine Linija u kojoj se greska nalazi
     */

    public function error_handler($numberOfErrors, $string, $file, $onLine)
    {

        $this->callErrorController("error_505");
    }


    /**
     * Razdvaja URL (kontroler,argumente u array).
     *
     * @return Array String $url("controller", "actionPage", "urlParams")
     */

    public function urlSpliter()
    {
        isset($_GET["url"]) ? $url = $_GET["url"] : $url = null;
        if (! is_null($url)) {
            $tmp_url = trim($url, "/"); // Ukloni duplirane oznake /.
            $tmp_url = trim($tmp_url, ".php"); // Ukloni PHP Ektenziju.
            $tmp_url = filter_var($tmp_url, FILTER_SANITIZE_URL); // Ukloni zabranjene karaktere.
            $tmp_url = explode("/", $tmp_url); // Prebaci u Array.

            $url = array();
            $url["controller"] = isset($tmp_url[0]) ? ucwords($tmp_url[0] . "Controller") : null;
            $url["actionPage"] = isset($tmp_url[1]) ? $tmp_url[1] : "index";

            unset($tmp_url[0], $tmp_url[1]);
            $url["urlParams"] = array_values($tmp_url);
            return $url;
        }
    }

    /**
     * Poziva Error Kontoler i izbacuje error korisniku.
     *
     * @param String $type
     * Vrsta Errora (npr: error_505, error_404...)
     */
    public function callErrorController($type)
    {
        $this->callErrorNum ++;
        require_once (ROOT_CONTROLLER_ERROR);
        $Controller = new \controller\ErrorController();
        call_user_func(array(
            $Controller,
            $type
        ));
        exit(1);
    }

    private function callHomeController()
    {
        require_once (ROOT_CONTROLLER_INDEX);
        $Controller = new \controller\IndexController();
        call_user_func(array(
            $Controller,
            "index"
        ));
        exit(1);
    }

    /**
     * Poziva kontroler prema URL.
     */
    public function route()
    {
        $url = $this->urlSpliter();
        if (is_array($url) && ! is_null($url["controller"])) {
            $controller = $url["controller"];
            $controllernm = "controller\\" . $controller;
            if (file_exists(ROOT . "controller/" . $controller . ".php")) {
                require ROOT . "controller/" . $controller . ".php";
                if (method_exists($controllernm, $url["actionPage"])) {
                    $controller_instance = new $controllernm();
                    if ($url["urlParams"]) {
                        call_user_func_array(array(
                            $controller_instance,
                            $url["actionPage"]
                        ), $url["urlParams"]);
                    } else {
                        call_user_func(array(
                            $controller_instance,
                            $url["actionPage"]
                        ));
                    }
                } else {
                    $this->callErrorController("error_404");
                }
            } else {
                $this->callErrorController("error_404");
            }
        } else {

            $this->callHomeController();
        }
    }

    public function pageRender($file, $pageData = array())
    {
        require_once ROOT . 'vendor/autoload.php';
        
        if(empty($pageData["scripts"]))
            $pageData["scripts"] = array();
        
            if(empty($pageData["links"]))
                $pageData["links"] = array();
        
        $data = array(
            "site" => $this->siteInfo->returnInfo(),
            "user" => $this->userInfo,
            "page" => $pageData,
            "scripts" => $this->getScripts("scripts", $pageData["scripts"]),
            "links" => $this->getScripts("links", $pageData["links"])
        );
       
        
        $loader = new \Twig\Loader\FilesystemLoader(ROOT_VIEW);
        $twig = new \Twig\Environment($loader);
        
        $filter = new \Twig\TwigFilter('pathFilter', function ($string) {
            if(filter_var($string, FILTER_VALIDATE_URL)) {
                return $string;
            } else {
                return ROOT_APP . "assets/" . $string;
            }
        });
        
        $twig->addFilter($filter);

        if(empty($pageData["removeBody"]))
            $pageData["removeBody"] = false;
        
            if ($this->renderNum == 0 && (!$pageData["removeBody"]))
            echo $twig->render("templates/header" . ".html", $data);

        try {

            echo $twig->render($file . ".html", $data);
        } catch (ParseError $parse) {
            require (ROOT_VIEW . "ErrorView/error_505" . ".php");
        }

        if ($this->renderNum == 0 && ! $pageData["removeBody"])
            echo $twig->render("templates/footer" . ".html", $data);

        $this->renderNum ++;
    }

    public function getParams()
    {
        return $this->urlSpliter();
    }

    public function disallowDirectPageAccess($query)
    {
        if ($query == $_SERVER["QUERY_STRING"]) {
            $this->pageRender("ErrorView/error_505");
            exit(1);
        }
    }
    /*
     * public function callDatabaseModel() {
     * require ROOT_MODEL . "DatabaseModel/" . DB_TYPE . "Database.php";
     * $Database_Class = DB_TYPE . "Database";
     * }
     */
    
    
    public function getScripts($type, $scripts = array()) {
        file_exists(ROOT_ASSETS . "all.json") ? $router_file = ROOT_ASSETS . "all.json" : $router_file = null;
        if($router_file != null && ($type == "scripts" || $type == "links")) {
            try {
                $file_contents = file_get_contents($router_file);
                $file_decode = json_decode($file_contents, true);
                $all_scripts = $file_decode[$type];
                
                $inc_scripts["header"] = array();
                $inc_scripts["footer"] = array();
                $header_scripts = array();
                $footer_scripts = array();
                
                foreach($scripts as $script) {
                    foreach($all_scripts as $all_script) {
                        if($script == $all_script["id"]) {
                            if($all_script["position"] == "header") {
                                $header_scripts[] = $all_script;
                            } else if($all_script["position"] == "footer") {
                                $footer_scripts[] = $all_script;
                            }
                        } 
                    } 
                }
                $inc_scripts["header"] = $header_scripts;
                $inc_scripts["footer"] = $footer_scripts;
                return $inc_scripts;
                
            } catch (Exception $e) {
                
            }
        } 
    }
    
    
    public function requireModel($path)
    {
        require_once ROOT_MODEL . $path . ".php";
    }

    public function requireDatabase()
    {
        $this->requireModel("databases/" . DB_TYPE . "Database");
    }

    public function requireSiteInfo()
    {
        $this->requireModel("/sitemanager/SiteInfo");
    }
}
