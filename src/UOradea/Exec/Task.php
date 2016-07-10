<?php

namespace UOradea\Exec;

abstract class Task extends Node {
    
    private $vars;
    
    final public function __construct(IVariables $vars, array $settings) {
        $this->vars = $vars;
        $this->init($settings);
    }
    
    protected function getVars() {
        return $this->vars;
    }
    
    abstract protected function init(array $settings);
    
    abstract public function execute();
    
    public function executeChildren() {
        foreach($this->walkChildren() as $child) {
            $child->execute();
        }
    }    
}