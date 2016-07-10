<?php

namespace UOradea\Exec\Task;

use UOradea\Exec\Task;

class Start extends Task {
    
    protected $name;
    
    protected function init(array $settings) {
        $this->name = $settings['name'];
    }
    
    public function execute() {
        echo 'Started ', $this->name, PHP_EOL;
        $this->executeChildren();
    }    
}