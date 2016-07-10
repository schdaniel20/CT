<?php

use UOradea\Exec\Program;
use UOradea\Exec\Task\Start;
use UOradea\Exec\Task\Increment;
use UOradea\Exec\Task\EchoTask;
use UOradea\Exec\Task\Loop;
use UOradea\Exec\Task\LoopInput;
use UOradea\Exec\Task\Curl;
use UOradea\Exec\Task\DOMExtract;

//p1. titlu
//p2. scopul lucrari
//p3. parti scrise ; doar esetiale, prezentarea tehnici; aplicatie; partile cheie


require_once 'vendor/autoload.php';

$reg = [
    'start' => Start::class,
    'increment' => Increment::class,
    'echo' => EchoTask::class,
    'loop' => Loop::class,
    'loopInput' => LoopInput::class,
    'setUrlParam' => SetUrlParam::class,
    'curl' => Curl::class,
    'extract' => DOMExtract::class,
];

$conf = [
    'program' => 'Test',
    'vars' => ['baseUrl' => 'http://localhost:8000/testing.php?limit=10',
               'i' => 1 ,
               'input' => [
                            [
                                "limit" => 10000                              
                            ],
                            [
                               "limit" => 1000000                          
                            ]
                           
                           ]               
               ],
    'run' =>
    [
        [
            'step' => 'loop',
            'settings' => [
                'var' => 'i',
                'start' => 1,
                'end' => 1100000,
                'inc' => 100000,
                
            ],
            'children' =>   [
                [
                    'step' => 'curl',
                    'settings' => [
                        'method' => 'GET',
                        //'postFields' => "longitude={value.lng}&latitude={value.lat}&searchfield={value.sf}&radius={value.radius}",
                        'url' => 'http://localhost:8000/testing.php?limit={i}',
                        'param' => "value",
                        'header' => ["CURLOPT_USERAGENT" => 'Mozilla/5.0 (X11; Linux x86_64; rv:41.0'],
                        'timeout' => 5,
                        'maxRequests' => 10,
                        'output' => 'page',
                        'debug' => true,
                    ]
                ],
                //[
                //    'step' => "extract",
                //    'settings' => [
                //                'xpath' => '//span[@itemprop="name"]',
                //                'function' => 'find',
                //                'source' => 'page',
                //                'output' => 'store.name',
                //                'type' => 'text',
                //                ]
                //],
                //[
                //    'step' => "extract",
                //    'settings' => [
                //                'xpath' => '//span[@itemprop="streetAddress"]',
                //                'function' => 'find',
                //                'source' => 'page',
                //                'output' => 'store.address',
                //                'type' => 'text',
                //                ]
                //],
                //[
                //    'step' => 'echo',
                //    'settings' => [
                //        'text' => '$page',
                //    ]
                //],
            ]
        ]
    ]  
];

$p = new Program($reg, $conf);
$p->run();





die;








//http://www.tchibo.de/tchibo-filiale-grosskoelnstrasse-48-52062-aachen-a1412648.html

$a = json_decode('{
"program":"Test",
"vars" : {"i" : 0,
"input" : [{
                "lng" : 13.404953999999975,
                "lat" : 52.52000659999999,
                "sf" : "Berlin",
                "radius" : 10000                                
            },
            {
                "lng" : 11.404953999999975,
                "lat" : 42.52000659999999,
                "sf" : "Bonn",
                "radius" : 10                                
            }]
},
"run" :
    [{
        "step":"loopInput",
        "settings" : {
                    "var" : "a",
                    "start" : 0,
                    "end" : {
                        "function" : "count",
                        "var" : "input"
                    },                
                    "inc" : 1,
                    "input" : "input",
                    "name" : "valueInput"
                }
,"children" :   [
    {
        "step" : "curl",
        "settings" : {
            "method" : "POST",
            "postFields" : "longitude={value.lng}&latitude={value.lat}&searchfield={value.sf}&radius={value.radius}",
            "url" : "$baseUrl",
            "param" : "value",
            "header" : {"CURLOPT_USERAGENT" : "Mozilla/5.0 (X11; Linux x86_64; rv:41.0"},
            "timeout" : 5,
            "maxRequests" : 10,
            "output" : "page",
            "debug" : true
        }
    },
    "step":"echo",
    "settings": {
        "text":"$page"
    }
]
    }]
}', true);

//print_r($a);die;

$b = '{
"step" : "curl",
"settings" : 
    {
    "method" : "GET",
    "postFields" : "{param}",
    "url" : "baseUrl",
    "param" : "value",
    "header" : {"CURLOPT_USERAGENT" : "Mozilla/5.0 (X11; Linux x86_64; rv:41.0"},
    "timeout" : "5",
    "maxRequests" : "10",
    "output" : "page",
    "debug" : "false"   
    }
}';

$b = json_decode($b,true);