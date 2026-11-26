--TEST--
stream_supports_lock() on a userspace wrapper tests for stream_lock() without calling it
--FILE--
<?php

class WithLock {
    public $context;
    public static $calls = [];
    public function stream_open($path, $mode, $options, &$opened_path) { return true; }
    public function stream_lock($operation) {
        self::$calls[] = $operation;
        return true;
    }
}

class WithoutLock {
    public $context;
    public function stream_open($path, $mode, $options, &$opened_path) { return true; }
}

stream_wrapper_register('with', 'WithLock');
stream_wrapper_register('without', 'WithoutLock');

$with = fopen('with://x', 'r');
$without = fopen('without://x', 'r');

var_dump(stream_supports_lock($with));
var_dump(WithLock::$calls);
var_dump(stream_supports_lock($without));
var_dump(flock($with, LOCK_SH));
var_dump(WithLock::$calls);

?>
--EXPECT--
bool(true)
array(0) {
}
bool(false)
bool(true)
array(1) {
  [0]=>
  int(1)
}
