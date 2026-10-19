--TEST--
phpversion() does not truncate the extension name at a NUL byte
--FILE--
<?php
var_dump(phpversion("standard\0x"));
var_dump(extension_loaded("standard\0x"));
var_dump(is_string(phpversion("standard")));
?>
--EXPECT--
bool(false)
bool(false)
bool(true)
