<?php
/*
 * @Author: your name
 * @Date: 2021-02-25 20:12:32
 * @LastEditTime: 2021-03-10 20:00:14
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /exponential-backoff/tests/ExponentialBackOffTest.php
 */

namespace Jessehu\ExponentialBackOffTest;

use Jessehu\ExponentialBackOff\ExponentialBackOff;
use Jessehu\ExponentialBackOffTest\Handler;
use PHPUnit\Framework\TestCase;

class ExponentialBackOffTest extends TestCase {
    /**
     * @expectException \Exception
     */
    public function testExecute01() {
        $exponentialBackOff = new ExponentialBackOff();
        
        $res = $exponentialBackOff->execute(function () {
            echo 'executing', PHP_EOL;
            throw new \Exception('failure', 500);
            return true;
        });
        $this->assertEquals($res, true);
    }

     /**
     * @expectException \Exception
     */
    public function testExecute02() {
        $exponentialBackOff = new ExponentialBackOff(10, function ($exception, $attempt) {
            if ($attempt < 2) {
                return true;
            }
            return false;
        });

        $exponentialBackOff->execute(function () {
            echo 'executing', PHP_EOL;
            throw new \Exception('failure'); 
        });
    }

    public function testExecuteByMethod() {
        $exponentialBackOff = new ExponentialBackOff(10);
        $done = $exponentialBackOff->execute([new Handler(), 'doTask'], ['eating']);
        $this->assertEquals($done, true);
    }

    public function testExecuteByStaticMethod() {
        $exponentialBackOff = new ExponentialBackOff(10);
        $done = $exponentialBackOff->execute([Handler::class, 'relax'], [10]);
        $this->assertEquals($done, true);
    }
}