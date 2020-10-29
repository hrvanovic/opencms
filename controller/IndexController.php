<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

use \model as model;
use \model\DatabaseModel as dbModel;

class IndexController
{
    private $SiteInfo;
    private $Database;
    private $data;
    
    public function __construct() {
       // global $core;
        // $core->callDatabaseModel();
    }
    
    function index()
    {
        global $core;
       
        $this->data = array(
            "header" => "fixed",
            "removeBody" => false,
            "pageInfo" => array(
                "title" => "IndexPage",
                "desc" => "Welcome",
                "author" => null,
                "keywords" => null,
                "featured-image" => "",
            ),
            "links" => array("style", "font/roboto", "material", "icons/material", "home"),
            "scripts" => array("jquery", "material", "app", "ajax-preloader")
        );
        
        $core->pageRender("IndexView/index", $this->data);
    }
    

    function part_dataTable()
    {
        global $core;
        $core->disallowDirectPageAccess("url=index/part_dataTable/");
        sleep(5);
        $core->pageRender("IndexView/dataTable", array(), false, true);
    }
}