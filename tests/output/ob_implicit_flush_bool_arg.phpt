--TEST--
ob_implicit_flush(): bool argument is parsed into a bool
--FILE--
<?php
var_dump(ob_implicit_flush(false));
var_dump(ob_implicit_flush(true));
var_dump(ob_implicit_flush());
var_dump(ob_implicit_flush(0));
?>
--EXPECT--
NULL
NULL
NULL
NULL
