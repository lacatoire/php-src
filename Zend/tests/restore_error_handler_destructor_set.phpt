--TEST--
restore_error_handler(): destructor of popped handler calls set_error_handler()
--FILE--
<?php

class S { function __destruct() { echo "X freed\n"; } }
class D {
    function __destruct() {
        set_error_handler($GLOBALS['X']);
    }
}

function base($n, $s) { return true; }

set_error_handler('base');
$s = new S;
$X = function () use ($s) {};
$s = null;
$d = new D;
set_error_handler(function () use ($d) {});
$d = null;

restore_error_handler();
$GLOBALS['X'] = null;
var_dump(get_error_handler() instanceof Closure);
restore_error_handler();
var_dump(get_error_handler());
echo "end of script\n";

?>
--EXPECT--
bool(true)
X freed
string(4) "base"
end of script
