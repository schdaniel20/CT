<?php

namespace UOradea\Exec\Task;

use UOradea\Exec\Task;

class Curl extends Task {
    protected $url;
    protected $header;
    protected $settings;
    protected $param;
    protected $postFields;
    protected $method;
    protected $response;
    protected $timeout;
    protected $maxRequests;
    protected $debug;
    protected $output;
    
    protected $curl;
    
    protected function init(array $settings) {
        $this->url = $settings['url'];
        $this->header = $settings['header'] ?? null;
        $this->postFields = $settings['postFields'] ?? '';
        $this->param = $settings['param'] ?? null;
        $this->method = $settings['method'] ?? 'GET';
        $this->response = $settings['response'] ?? 'response';
        $this->timeout = $settings['timeout'] ?? 10;
        $this->maxRequests = $settings['maxRequests'] ?? 1;
        $this->debug = $settings['debug'] ?? true;
        $this->output = $settings['output'] ?? 'page';
    }
        
    protected function setVars(string $text) {
        $vars = $this->getVars();
        
        if($text[0] == "$") {
            $text = $vars->get(substr($text, 1));
        }
        
        return preg_replace_callback('/\{([a-z0-9_\.]+?)\}/sim',
        function($m) use ($vars) {            
           
            $value = $vars->get($m[1]);
            if ($value !== null) {
                return $value;
            }
            return $m[0];            
        }, $text);
    }

    protected function setCurl(array $header) {
        $url = $this->setVars($this->url);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        
        foreach($header as $key => $value) {
            curl_setopt($this->curl, constant($key), $value);
        }               
        
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($this->curl, CURLOPT_VERBOSE, false);
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        
        if($this->method === 'POST') {
            $post = $this->setVars($this->postFields);
            
            curl_setopt ( $this->curl, CURLOPT_POST, 1 );
            curl_setopt ( $this->curl, CURLOPT_POSTFIELDS, $post); 
        }
        
    }
    
    public function execute() {
       
        $this->curl = curl_init();
        $this->setCurl($this->header);        
        $response = curl_exec($this->curl);

        $vars = $this->getVars();
        $vars->set($this->output, $response);

        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        
        if($this->debug === true){
            print_r($this->statistic($header));
        }
        
        curl_close($this->curl);
    }
    
    protected function statistic(string $header) {
        $stat = [];
        $stat['method'] = $this->method;
        $stat['url'] = $this->setVars($this->url);
        
        if($this->method === "POST") {
            $stat['postFields'] = $this->setVars($this->postFields);
        }
    
        $stat['header'] = $header;
        
        $stat['total_time'] = curl_getinfo($this->curl, CURLINFO_TOTAL_TIME);
        $stat['connect_time'] = curl_getinfo($this->curl, CURLINFO_CONNECT_TIME);
        
        $stat['size'] = round((curl_getinfo($this->curl, CURLINFO_SIZE_DOWNLOAD) / 1024) / 1024 , 4) . 'MB';
        
        return $stat;
        
    }
}