<?php

namespace model\sitemanager;

class SiteInfo {
    public $info = array();
   
    public function __construct() {
        $this->setInfo("site_name", "Pik.ba");
        $this->setInfo("site_ef", "hahaha");

        $this->setInfo("sidebarMenuItems", array());
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
