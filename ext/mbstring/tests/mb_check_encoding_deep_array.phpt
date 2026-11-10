--TEST--
mb_check_encoding() stops at the stack limit on a deeply nested array
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip The stack limit check is not reliable on Windows');
}
?>
--INI--
zend.max_allowed_stack_size=256K
memory_limit=-1
--FILE--
<?php

$array = "x";
for ($i = 0; $i < 20000; $i++) {
    $array = [$array];
}

try {
    var_dump(mb_check_encoding($array, 'UTF-8'));
} catch (Error $e) {
    echo get_class($e), ': ', str_starts_with($e->getMessage(), 'Maximum call stack size of') ? 'stack limit' : $e->getMessage(), "\n";
}

?>
--EXPECT--
Error: stack limit
