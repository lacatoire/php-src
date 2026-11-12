--TEST--
sscanf() character sets starting with "]" or "-" and ending with "-"
--FILE--
<?php
var_dump(sscanf("--a", "%[--]"));
var_dump(sscanf("]-a", "%[]-]"));
var_dump(sscanf("a-]", "%[^--]"));
var_dump(sscanf("a]-", "%[^]-]"));
var_dump(sscanf("-a-b", "%[-a-]"));
?>
--EXPECT--
array(1) {
  [0]=>
  string(2) "--"
}
array(1) {
  [0]=>
  string(2) "]-"
}
array(1) {
  [0]=>
  string(1) "a"
}
array(1) {
  [0]=>
  string(1) "a"
}
array(1) {
  [0]=>
  string(3) "-a-"
}
