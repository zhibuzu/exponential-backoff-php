<!--
 * @Author: your name
 * @Date: 2021-02-25 20:41:01
 * @LastEditTime: 2021-02-25 20:46:57
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /exponential-backoff/README.md
-->
# exponential-backoff-php
php实现的指数退避算法

# Usage
```php
# example01: 默认重试3次
$exponentialBackOff = new ExponentialBackOff();
$exponentialBackOff->execute(function () {
    // do some staff, like request api
    // ...
    if (/*failure*/) {
        throw new \Exception('failure');
    }
});

# example02: 指定重试次数、判断是否重试方法（返回true则重试，false则直接失败）
$exponentialBackOff = new ExponentialBackOff(10, function ($exception, $attempt) {
    if ($attempt < 2) {
        return true;
    }
    return false;
});
$exponentialBackOff->execute(function () {
    // do some staff, like request api
    // ...
    if (/*failure*/) {
        throw new \Exception('failure');
    }
});
```