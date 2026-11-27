--TEST--
trim(): error handler releasing the arguments of a frameless call
--FILE--
<?php
$string = 'Q' . str_repeat('x', 38) . 'Q';
$characters = 'z..a' . str_repeat('Q', 39);
set_error_handler(function (int $errno, string $errstr) {
    global $string, $characters;
    echo $errstr, "\n";
    $characters = null;
    $string = null;
    $GLOBALS['filler'] = str_repeat('Z', 40);
    return true;
});
var_dump(trim($string, $characters));
?>
--EXPECT--
trim(): Invalid '..'-range, '..'-range needs to be incrementing
string(38) "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
