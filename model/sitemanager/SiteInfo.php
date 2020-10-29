<?php

namespace model\SiteManager;

class SiteInfo {
    public $info = array();
   
    public function __construct() {
        $this->setInfo("site_name", "OpenCMS");
        $this->setInfo("site_ef", "hahaha");
    }
    
    public function getInfo($info) {
        return $this->info[$info];
    }

    public function setInfo($info, $data) {
        $this->info[$info] = $data;
    }
    
    public function returnInfo() {
        return $this->info;
    }
}
