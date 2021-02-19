<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    namespace model\databases;

    use PDO;
    use Exception;

    class PDODatabase
    {
        private $db = null;

        public function __construct()
        {
            $this->connect();
        }

        private function connect()
        {
            try {
                $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
              //  $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            } catch (Exception $exception) {
                global $core;
                $core->callErrorController("error_505");
                $core->SystemLog()->newLog($exception, "error");
            }
        }

        public function prepare($query, $params = null)
        {
            return PDODatabaseQuery::prepareQuery($query, $params, $this->db);
        }

        public function fetchAll($query) {
            $fetch = $query->fetch(PDO::FETCH_ASSOC);
            $array = array();

            foreach ((array) $fetch as $row) {
                $array[] = $row;
            }
            return $array;
        }

        public function escapeString($string)
        {
            return $string;
        }

        public function getConn()
        {
            return $this->db;
        }
    }

    class PDODatabaseQuery {
        public function __construct($query, $params, $db)
        {
            self::prepareQuery($query, $params, $db);
        }

        public static function prepareQuery(string $query, array $params = null, PDO $db) {
            $prepared = $db->prepare($query);

            if (!is_null($params)) {
                for ($i = 0; $i < count($params); $i++) {
                    $prepared->bindParam(($i + 1), $params[$i]);
                }
            };
            return $prepared;
        }
    }