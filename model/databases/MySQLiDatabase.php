<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace model\databases;

use mysqli;
use PDO;
use Exception;

class MySQLiDatabase {
    
    private $db = null;
    private $prepared = null;
    private $result = null;
    private $executed = false;
    
    public function __construct() {
        $this->connect();
    }
   
    private function connect() {
        
        try {
            $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        } catch (Exception $ex) {

        }
    }

    public function runQuery($query, $params = array()) {
        try {
            $prepared = $this->db->prepare($query);

            foreach ($params as $param) {
                $type = NULL;
                $type = $type . "s";
                echo $param . "<br>";
            }

            call_user_func_array(array($prepared, "bind_param"), array_merge(array($type), $params));
            $prepared->execute();
            $this->result = $prepared->get_result();
            $this->prepared = $prepared;
            $this->executed = true;

            return $prepared;
        } catch (Exception $exception) {
            echo $exception;
        }
    }

    public function escapeString($string) {
        return $this->db->real_escape_string($string);
    }

    public function isExecuted() {
        return $this->executed;
    }

    public function getObject($object) {
        /*
         * JEBE NA PHP 7.4, NIJE MALO.
            $fetch = $this->getResult()->fetch_all();
           // print_r($fetch);
            echo $fetch[0][2];
        */
    }

    public function getResult() {
        return $this->result;
    }

    public function getNumRows() {
        try {
            return $this->result->num_rows;
        } catch (Exception $e) {

        }
    }

    public function getConn() {
        return $this->db;
    }
    
    public function connect_errno() {
        return $this->db->connect_errno;
    }
    
}