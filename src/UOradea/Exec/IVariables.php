<?php

namespace UOradea\Exec;

interface IVariables {
    
    public function get(string $var);
    
    public function set(string $var, $value);
    
    public function del(string $var);
    
    public function has(string $var);
}