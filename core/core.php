<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Core {

    /**
     * Razdvaja URL u array.
     * 
     * @return Array String $url("controller", "actionPage", "urlParams")
     */
    private function urlSpliter() {
        isset($_GET["url"]) ? $url = $_GET["url"] : $url = null;

        if (!is_null($url)) {
            $tmp_url = trim($url, "/"); // Ukloni duplirane oznake /.
            $tmp_url = filter_var($tmp_url, FILTER_SANITIZE_URL); // Ukloni zabranjene karaktere.
            $tmp_url = explode("/", $tmp_url); // Prebaci u Array.

            $url = array();
            $url["controller"] = isset($tmp_url[0]) ? ucwords($tmp_url[0] . "Controller") : null;
            $url["controllerFile"] = $url["controller"] . ".php";
            $url["actionPage"] = isset($tmp_url[1]) ? $tmp_url[1] : "index";

            unset($tmp_url[0], $tmp_url[1]);
            $url["urlParams"] = array_values($tmp_url);
            return $url; 
        }
    }
    
    public function getParams() {
        return $this->urlSpliter();
    }

    /**
     * Poziva Error Kontoler i izbacuje error korisniku.
     * 
     * @param String $type Vrsta Errora (npr: error_505, error_404...)
     */
    private function callErrorController($type) {
        require(ROOT_CONTROLLER_ERROR);
        call_user_func($type);
    }
    
    private function callIndexController() {
        require(ROOT_CONTROLLER_INDEX);
        call_user_func("index");
    }

    /**
     * Poziva kontroler prema URL.
     */
    public function route() {
        $url = $this->urlSpliter();

        if (is_array($url) && !is_null($url["controller"])) {
            $controller = $url["controllerFile"];   
            if (file_exists(ROOT . "controller/" . $controller)) {
                require ROOT . "controller/" . $controller;

                if (function_exists($url["actionPage"])) {
                    if ($url["urlParams"]) {
                        call_user_func_array($url["actionPage"], $url["urlParams"]);
                    } else {
                        call_user_func($url["actionPage"]);
                    }
                } else {
                    $this->callErrorController("error_404");
                }
            } else {
                $this->callErrorController("error_404");
            }
            
        } else {
            
            $this->callIndexController();
            
        }
        
     
    }
    
    public function pageRender($file, $data = null) {
        
        if(!is_null($data)) {
            foreach($data as $key => $value) {
                $$key = $value;
            }
        }
        
        require(ROOT_VIEW . "templates/header.php");
        require(ROOT_VIEW . $file . ".php");
        require(ROOT_VIEW . "templates/footer.php");
    }
                

}
