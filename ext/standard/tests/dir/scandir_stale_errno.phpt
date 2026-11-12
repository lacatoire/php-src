--TEST--
scandir() does not report a stale errno for streams without directory support
--FILE--
<?php
@file_get_contents("/nonexistent/x");
var_dump(scandir("php://memory"));
var_dump(scandir("data://text/plain,foo"));
?>
--EXPECTF--
Warning: scandir(): Failed to open directory: not implemented in %s on line %d
bool(false)

Warning: scandir(): Failed to open directory: not implemented in %s on line %d
bool(false)
