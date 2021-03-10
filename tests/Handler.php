<?php
/*
 * @Author: your name
 * @Date: 2021-03-10 19:44:02
 * @LastEditTime: 2021-03-10 19:59:33
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /exponential-backoff/tests/Handler.php
 */

namespace Jessehu\ExponentialBackOffTest;

class Handler
{
    public function doTask($task) {
        echo 'do something:', $task, PHP_EOL;
        return true;
    }

    public static function relax($seconds) {
        echo 'relax for a while:', $seconds, PHP_EOL;
        return true;
    }
}