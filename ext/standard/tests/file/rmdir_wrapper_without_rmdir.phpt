--TEST--
rmdir() warns when the wrapper does not support removing directories
--FILE--
<?php

var_dump(rmdir('php://memory'));
var_dump(rmdir('data://text/plain,x'));

?>
--EXPECTF--
Warning: rmdir(): %s does not allow removing directories in %s on line %d
bool(false)

Warning: rmdir(): %s does not allow removing directories in %s on line %d
bool(false)
