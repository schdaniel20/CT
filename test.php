<?php

use UOradea\Exec\Program;
use UOradea\Exec\Task\Start;
use UOradea\Exec\Task\Increment;
use UOradea\Exec\Task\EchoTask;
use UOradea\Exec\Task\Loop;
use UOradea\Exec\Task\LoopInput;
use UOradea\Exec\Task\Curl;
use UOradea\Exec\Task\DOMExtract;
use UOradea\Exec\Helper;

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

$args = Helper::getArgumentMap($argv);

if(!empty($args)) {
    $conf = Helper::getJsonFileToArray($args['-f']);
    $program = new Program($reg, $conf);
    $program->run();
}
else {
    throw new Exception("Can't find the -f argument that points to the configFile!");
}

