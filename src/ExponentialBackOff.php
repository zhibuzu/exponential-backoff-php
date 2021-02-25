<?php
/*
 * @Author: your name
 * @Date: 2021-02-25 17:50:27
 * @LastEditTime: 2021-02-25 20:37:52
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /exponential-backoff/src/ExponentialBackOff.php
 */

namespace Jessehu\ExponentialBackOff;

class ExponentialBackOff {
    const MAX_DELAY_MICROSECONDS = 60000000;

    /**
     * 重试次数
     *
     * @var int
     */
    private $retries;

    /**
     * 判断是否重试方法
     *
     * @var callable|null
     */
    private $retryFunc;

    /**
     * 延迟方法
     *
     * @var callable|null
     */
    private $delayFunc;

    /**
     * 计算延迟时间方法
     *
     * @var callable|null
     */
    private $calcDelayFunc;

    /**
     * @param int $retries [optional] 失败请求的重试次数
     * @param callable $retryFunc [optional] 函数返回布尔值来决定是否重试
     */
    public function __construct($retries = null, callable $retryFunc = null) {
        $this->retries = $retries ? (int)$retries : 3;
        $this->retryFunc = $retryFunc;
    }

    /**
     * 执行重试程序
     *
     * @param callable $func
     * @param array $arguments [optional]
     * @return mixed
     * @throws \Exception 最后一次重试时捕获的异常
     */
    public function execute(callable $func, array $arguments = []) {
        $delayFunc = $this->delayFunc ?: [$this, 'delayFunc'];
        $calcDelayFunc = $this->calcDelayFunc ?: [$this, 'calcDelayFunc'];
        $attempt = 0;
        $exception = null;

        while (true) {
            try {
                return \call_user_func_array($func, $arguments);
            } catch (\Exception $exception) {
                if ($this->retryFunc && !call_user_func($this->retryFunc, $exception, $attempt)) {
                    throw $exception;
                }

                if ($attempt >= $this->retries) {
                    break;
                }

                $delay = $calcDelayFunc($attempt);
                $delayFunc($delay);
                ++$attempt;
            }
        } 

        throw $exception;
    }

    /**
     * 假如没设置默认使用`usleep`
     *
     * @param callable $delayFunc
     * @return void
     */
    public function setDelayFunc(callable $delayFunc) {
        $this->delayFunc = $delayFunc;
    }

    /**
     * 假如没设置默认使用
     * {@see Jessehu\ExponentialBackOff\ExponentialBackOff::calcDelayFunc}
     *
     * @param callable $calcDelayFunc
     * @return void
     */
    public function setCalcDelayFunc(callable $calcDelayFunc) {
        $this->calcDelayFunc = $calcDelayFunc;
    }

    /**
     * 默认延迟方法
     *
     * @param int $delay The microseconds of delay
     * @return void
     */
    public function delayFunc($delay) {
        usleep($delay);
    }

    /**
     * 默认指数退避算法计算延迟时间
     *
     * @param int $attempt 用来计算延迟时间的重试次数
     * @return void
     */
    public function calcDelayFunc($attempt) {
        return min(
            mt_rand(0, 1000000) + (pow(2, $attempt) * 1000000),
            self::MAX_DELAY_MICROSECONDS
        );
    }


}