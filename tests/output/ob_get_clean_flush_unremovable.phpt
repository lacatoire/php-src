--TEST--
ob_get_clean() and ob_get_flush() return false when the buffer cannot be removed
--FILE--
<?php
ob_start(null, 0, 0);
echo "AAA\n";
var_dump(@ob_get_clean());
var_dump(ob_get_level());

ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_REMOVABLE);
echo "BBB\n";
var_dump(@ob_get_flush());
var_dump(ob_get_level());
?>
--EXPECT--
AAA
bool(false)
int(1)
BBB
bool(false)
int(2)
