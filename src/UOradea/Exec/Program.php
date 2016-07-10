<?php

namespace UOradea\Exec;

use Exception;

class Program {

    protected $vars;
    protected $start;
    protected $map = [];
    
    public function __construct(array $registry, array $config) {
        $this->map = $registry;
        $this->vars = new Variables();
        $this->setVars($config['vars']);
        $this->start = $this->factory('start', ['name' => $config['program']]);
        $this->parse($config['run'], $this->start);
    }
    
    protected function setVars(array $vars) {
        foreach($vars as $name => $value) {
            $this->vars->set($name, $value);
        }
    }
    
    protected function parse(array $nodes, Task $parent) {
        foreach($nodes as $node) {
            $task = $this->factory($node['step'], $node['settings']);
            if (!empty($node['children'])) {
                $this->parse($node['children'], $task);
            }
            $parent->appendChild($task);
        }
        return $parent;
    }
    
    public function run() {
        $this->start->execute();
    }   
    
    protected function factory(string $task, array $settings) : Task {
        if (!isset($this->map[$task])) {
            throw new Exception("Task " . $task . " cannot be handled!");
        }
        $class = $this->map[$task];
        
        return new $class($this->vars, $settings);
    }
}