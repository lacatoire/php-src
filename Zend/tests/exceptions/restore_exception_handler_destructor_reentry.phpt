--TEST--
restore_exception_handler() destroys the replaced handler once the handler stack is consistent
--FILE--
<?php

class RestoringDestructor {
    function __destruct() {
        restore_exception_handler();
    }
}

set_exception_handler(function () { echo "first\n"; });
$d = new RestoringDestructor;
set_exception_handler(function () use ($d) { echo "second\n"; });
$d = null;
restore_exception_handler();
echo "restored once\n";
var_dump(get_exception_handler() === null);

class SettingDestructor {
    function __destruct() {
        set_exception_handler(function () { echo "set by destructor\n"; });
    }
}

restore_exception_handler();
set_exception_handler(function () { echo "base\n"; });
$d = new SettingDestructor;
set_exception_handler(function () use ($d) { echo "captured\n"; });
$d = null;
restore_exception_handler();
throw new Exception("x");

?>
--EXPECT--
restored once
bool(true)
set by destructor
