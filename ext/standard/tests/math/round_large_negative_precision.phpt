--TEST--
round(): values of 2^53 or more with a negative precision are rounded exactly
--FILE--
<?php

var_dump(round(9007199254741004, -1));
var_dump(round(9007199254741006, -1));
var_dump(round(9007199254741005, -1, RoundingMode::HalfEven));
var_dump(round(9007199254741015, -1, RoundingMode::HalfEven));
var_dump(round(9007199254741005, -1, RoundingMode::HalfOdd));
var_dump(round(9007199254741001, -1, RoundingMode::PositiveInfinity));
var_dump(round(-9007199254741001, -1, RoundingMode::NegativeInfinity));
var_dump(round(PHP_INT_MAX, -18, RoundingMode::AwayFromZero));
var_dump(round(9007199254741004.0, -1));
var_dump(round(9007199254741003.0, -1));
var_dump(round(-9007199254741004.0, -1, RoundingMode::HalfTowardsZero));
var_dump(round(1.0869464255E+22, -8));

?>
--EXPECT--
float(9007199254741000)
float(9007199254741010)
float(9007199254741000)
float(9007199254741020)
float(9007199254741010)
float(9007199254741010)
float(-9007199254741010)
float(1.0E+19)
float(9007199254741000)
float(9007199254741000)
float(-9007199254741000)
float(1.0869464255E+22)
