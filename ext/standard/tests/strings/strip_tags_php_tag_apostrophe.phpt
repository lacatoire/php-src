--TEST--
strip_tags(): an apostrophe in a double-quoted string inside a PHP tag does not hide the closing tag
--FILE--
<?php

var_dump(strip_tags('<?php echo "it\'s"; ?>X'));
var_dump(strip_tags('<?php echo "it" . "x"; ?>X'));
var_dump(strip_tags('<?php echo "a\'b" . "c\'d"; ?>X<b>Y</b>'));
var_dump(strip_tags('<?php echo \'say "hi"\'; ?>X'));
var_dump(strip_tags('<?php echo "?>"; ?>X'));

?>
--EXPECT--
string(1) "X"
string(1) "X"
string(2) "XY"
string(1) "X"
string(1) "X"
