<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

global $core;
$core->requireMainController();

class IndexController extends Controller
{
    public function __construct() {
        global $core;
        parent::__construct();

        if($this->isLogged() == 0)
            $core->redirectUser("login");
    }
    
    function index()
    {
        global $core;

        $this->data = array(
            "header" => "fixed",
            "pageInfo" => array(
                "title" => "Welcome",
                "desc" => "Welcome",
                "author" => null,
                "keywords" => null,
                "featured-image" => "",
            ),
            "user" => $this->userInfo,
            "ajax" => array("welcome" => ROOT_APP . "/index/pageTemplate/welcome"),
            "links" => array("material", "font/roboto", "style", "icons/material", "home"),
            "scripts" => array("jquery", "material", "app", "ajax-loader")
        );

        $core->pageRender("Index/index", $this->data);
    }

    function pageTemplate() {
        global $core;

        if(isset($core->getParams()["urlParams"][0]) && $core->getParams()["urlParams"][0] == "welcome") {
            // $core->disallowDirectPageAccess("url=index/pageTemplate/welcome");
            $this->data = array(
                "header" => "fixed",
                "pageInfo" => NULL,
                "user" => $this->userInfo,
                "links" => NULL,
                "scripts" => NULL
            );
            $core->pageRender("Index/welcome", $this->data);
        }
    }
}