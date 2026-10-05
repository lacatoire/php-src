--TEST--
number_format() keeps the sign of -INF
--FILE--
<?php
var_dump(number_format(INF));
var_dump(number_format(-INF));
var_dump(number_format(-INF, 2, ",", "."));
var_dump(number_format(NAN));
?>
--EXPECT--
string(3) "inf"
string(4) "-inf"
string(4) "-inf"
string(3) "nan"
