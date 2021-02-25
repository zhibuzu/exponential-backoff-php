<?php
/*
 * @Author: your name
 * @Date: 2021-02-25 20:12:32
 * @LastEditTime: 2021-02-25 20:37:44
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /exponential-backoff/tests/ExponentialBackOffTest.php
 */

namespace Jessehu\ExponentialBackOffTest;

use Jessehu\ExponentialBackOff\ExponentialBackOff;
use PHPUnit\Framework\TestCase;

class ExponentialBackOffTest extends TestCase {
    /**
     * @expectedException \Exception
     */
    public function testExecute01() {
        $exponentialBackOff = new ExponentialBackOff();
        
        $exponentialBackOff->execute(function () {
            echo 'executing', PHP_EOL;
            throw new \Exception('failure');
        });
    }

     /**
     * @expectedException \Exception
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
}