<?php

namespace UOradea\Exec\Task;

use UOradea\Exec\Task;

class LoopInput extends Task {
    
    protected $var;
    protected $start;
    protected $end;
    protected $inc;
    protected $name;
    protected $input;
    
    protected function init(array $settings) {
        $this->var = $settings['var'];
        $this->start = $settings['start'] ?? null;
        $this->end = $settings['end'];
        $this->inc = $settings['inc'] ?? 0;
        $this->name = $settings['name'] ?? 'value';
        $this->input =  $settings['input'];
        
    }
    
    protected function rewind() {
        if ($this->start !== null) {
            $this->getVars()->set($this->var, $this->start);
        }
    }
    
    protected function isDone() : bool {
        
        $end = $this->end;
        $vars = $this->getVars();
        
        if(is_array($this->end)) {
            $f = $this->end['function'];
            $v = $this->end['var'];
            $end = $f($vars->get($v));
        }
        elseif (!is_numeric($end) && $end[0] === '$') {
            $end = $vars->get(substr($end, 1));
        }
        
        return $vars->get($this->var) >= $end;
    }
    
    protected function increment() {
        if (!$this->inc) {
            return;
        }
        $vars = $this->getVars();
        $vars->set($this->var, $vars->get($this->var) + $this->inc);
    }
    
    public function execute() {
        $this->rewind();
        $vars = $this->getVars();
        $input = $vars->get($this->input);
        while(!$this->isDone()) {
            
            $i = $vars->get($this->var);
           
            $vars->set($this->name, $input[$i]);
            
            $this->executeChildren();
            $this->increment();
        }
    }
}