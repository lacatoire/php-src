--TEST--
set_error_handler() and set_exception_handler() declare the previous handler they return
--FILE--
<?php

foreach ([
    'set_error_handler',
    'get_error_handler',
    'set_exception_handler',
    'get_exception_handler',
] as $function) {
    printf("%-23s %s\n", $function, (new ReflectionFunction($function))->getReturnType());
}

function first() {}
function second() {}

var_dump(set_error_handler('first'));
var_dump(set_error_handler('second') === 'first');
var_dump(set_exception_handler('first'));
var_dump(set_exception_handler('second') === 'first');

restore_error_handler();
restore_exception_handler();

?>
--EXPECT--
set_error_handler       ?callable
get_error_handler       ?callable
set_exception_handler   ?callable
get_exception_handler   ?callable
NULL
bool(true)
NULL
bool(true)
