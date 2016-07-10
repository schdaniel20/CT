<?php

namespace UOradea\Exec;

class Variables implements IVariables {
    
    protected $bin = [];
    
    public function get(string $name) {
        $path = explode('.', $name);
        
        $tmp = &$this->bin;
        
        foreach($path as $key) {
            if(!array_key_exists($key, $tmp)) {
                return null;
            }
            else {                
                $tmp = &$tmp[$key];
            }
        }
        return $tmp;
    }
    
    public function set(string $var, $value) {    
        $path = explode('.', $var);
        
        $tmp = &$this->bin;            
        foreach($path as $key) {
            
            if(@!array_key_exists($key, $tmp)) {           
                $tmp = &$tmp[$key];            
            }
            else {            
                $tmp[$key] = is_array($tmp[$key]) ? $tmp[$key] : [];
                $tmp = &$tmp[$key];
            }
        }
        $tmp = $value;   
    }
    
    public function del(string $var) {
        unset($this->bin[$var]);
    }
    
    public function has(string $var) {
        return isset($this->bin[$var]);
    }    
        
}