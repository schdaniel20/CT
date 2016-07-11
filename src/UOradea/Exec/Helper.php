<?php

namespace UOradea\Exec;

use Exception;

class Helper {
    
    public static function getArgumentMap($args) : array {        
        $count = count($args);
        $arguments = [];
        
        for($i = 1; $i < $count; $i++) {
            if($args[$i] === '-f') {
                $arguments[$args[$i]] = $args[++$i];
            }
        }        
        return $arguments;
    }
    
    public static function getJsonFileToArray($file) {
        if(file_exists($file)) {
            return  json_decode(file_get_contents($file), true);
        }
        throw new Exception("File not found : " . $file);
    }
}