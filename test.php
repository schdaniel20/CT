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

$conf = json_decode(file_get_contents('configExample/limit.json'), true);

$p = new Program($reg, $conf);
$p->run();
