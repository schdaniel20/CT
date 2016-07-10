<?php

namespace UOradea\Exec\Task;

use UOradea\Exec\Task;

class EchoTask extends Task {
    
    protected $text;
    
    protected function init(array $settings) {
        $this->text = $settings['text'];
    }
    
    public function execute() {
        $vars = $this->getVars();
        
        echo preg_replace_callback('/(?:\$([a-z 0-9_\.]+))/sim',
        function($m) use ($vars) {            
           
            $value = $vars->get($m[1]);
            if ($value !== null) {   
                if(is_array($value)) {
                    $value = implode(PHP_EOL, array_map(
                        function ($v, $k) {                            
                            return sprintf("%s => %s", $k, $v);
                            },
                        $value,
                        array_keys($value)
                    ));                    
                }
                return $value;
            }
            return $m[0];            
        }, $this->text), PHP_EOL;
    }    
}