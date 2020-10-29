<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace model\databases;

use mysqli;
use Exception;  

class MySQLiDatabase {
    
    private $db = null;
    
    public function __construct() {
        $this->connect();
    }
   
    private function connect() {
        
        try {
            $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        } catch (Exception $ex) {
            echo "err";
        }
    }
    
    public function getConnection() {
        return $this->db;
    }
    
    public function connect_errno() {
        return $this->db->connect_errno;
    }
    
    public function __destruct() {
        $this->db = null;
    }
    
}