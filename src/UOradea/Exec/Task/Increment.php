<?php

namespace UOradea\Exec\Task;

use UOradea\Exec\Task;

class Increment extends Task {
    
    protected $var;
    protected $value = 0;
    
    protected function init(array $settings) {
        $this->var = $settings['var'];
        $this->value = (float) $settings['value'];
    }
    
    public function execute() {
        $vars = $this->getVars();
        $var = $vars->get($this->var);
        $var += $this->value;
        $vars->set($this->var, $var);
        //$this->executeChildren();
    }    
}