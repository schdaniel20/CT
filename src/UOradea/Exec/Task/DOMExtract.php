<?php

namespace UOradea\Exec\Task;

use UOradea\Exec\Task;

class DOMExtract extends Task {
    protected $xpath;
    protected $function;
    protected $source;
    protected $output;
    protected $type;
    
    protected $dom;
    
    protected function init(array $settings) {
        $this->xpath = $settings['xpath'];
        $this->function = $settings['function'] ?? 'find';
        $this->source = $settings['source'];
        $this->output = $settings['output'];
        $this->type = $settings['type'] ?? null; 
    }
    
    protected function findElements() {
        $vars = $this->getVars();
        
        $source = $vars->get($this->source);
        $this->dom = new \DOMDocument();
        @$this->dom->loadHTML($source);
        
        $xpath = new \DOMXpath($this->dom);
        
        $elements = $xpath->query($this->xpath);
        return $elements;
    }
    
    protected function getType ($elements) {
        
        if($this->type === null) {
            return $elements;
        }
        
        $result = [];
        foreach($elements as $element) {
            //extract type, text, html attributes;
            switch ($this->type) {
            case 'text':
                $result[] = $element->nodeValue;
                break;
            case 'html' :
                $result[] = $this->dom->saveHTML($element);
                break;
            default :
                $result[] = $element->getAttribute($this->type);
            }
            
        }
        
        return $result;
    }
   
    public function execute() {
        $vars = $this->getVars();
        
        $result = $this->getType($this->findElements());

        if(empty($result)) {
            return;
        }elseif($this->function == 'findAll') {
            $vars->set($this->output, $result);
        }
        else {
            $vars->set($this->output, $result[0]);
        }
        
    }
}