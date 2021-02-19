<?php
namespace logs;

    class SystemLog
    {
        public static function newLog(string $contents, string $type = "logs")
        {
            $fileName = ($type == "error") ? "errors.txt" : "logs.txt";
            try {
                file_put_contents(ROOT_LOGS . $fileName, $contents);
            } catch (Exception $exception) { }
        }
    }