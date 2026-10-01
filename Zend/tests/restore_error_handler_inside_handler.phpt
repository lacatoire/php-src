--TEST--
restore_error_handler() called from inside the error handler removes the running handler
--FILE--
<?php
function h($n, $s) {
    echo "h: $s\n";
    restore_error_handler();
    return true;
}
set_error_handler('h');
trigger_error("1", E_USER_NOTICE);
var_dump(get_error_handler());
trigger_error("2", E_USER_NOTICE);

function h2($n, $s) {
    echo "h2: $s\n";
    set_error_handler('h');
    restore_error_handler();
    return true;
}
set_error_handler('h2');
trigger_error("3", E_USER_NOTICE);
var_dump(get_error_handler());
trigger_error("4", E_USER_NOTICE);
?>
--EXPECTF--
h: 1
NULL

Notice: 2 in %s on line %d
h2: 3
string(2) "h2"
h2: 4
