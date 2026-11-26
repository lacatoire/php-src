--TEST--
A nested parse in an error handler called during compilation does not corrupt the outer compilation
--FILE--
<?php
set_error_handler(function () {
    try {
        eval('function g() { yield 1; (');
    } catch (ParseError $e) {
    }
    return true;
});
eval('function f() { $v = (integer) 42; return $v; }');
var_dump(f());

set_error_handler(function () {
    token_get_all('<?php 1;');
    return true;
});
eval('class K { /** d */ const C = (integer) 1; }');
var_dump((new ReflectionClassConstant('K', 'C'))->getDocComment());
?>
--EXPECT--
int(42)
string(8) "/** d */"
