--TEST--
str_getcsv() and fgetcsv() do not reuse the width of the opening enclosure for the first byte of a field
--FILE--
<?php
var_dump(str_getcsv('"', ',', '"', "\0"));
var_dump(str_getcsv("\"\n", ',', '"', "\n"));

$stream = fopen('php://memory', 'w+');
fwrite($stream, "\"\nab");
rewind($stream);
var_dump(fgetcsv($stream, null, ',', '"', "\n"));
?>
--EXPECT--
array(1) {
  [0]=>
  string(0) ""
}
array(1) {
  [0]=>
  string(1) "
"
}
array(1) {
  [0]=>
  string(3) "
ab"
}
