--TEST--
round() directed modes do not return a value beyond the argument
--FILE--
<?php

// 0.8999999999999999 * 10 is rounded to exactly 9.0 by the multiplication.
var_dump(round(0.8999999999999999, 1, RoundingMode::TowardsZero));
var_dump(round(3.4699999999999998, 2, RoundingMode::TowardsZero));
var_dump(round(0.1 + 0.7, 2, RoundingMode::NegativeInfinity));
var_dump(round(-0.8999999999999999, 1, RoundingMode::PositiveInfinity));
var_dump(round(-0.8999999999999999, 1, RoundingMode::TowardsZero));
var_dump(round(0.8999999999999999, 1, RoundingMode::AwayFromZero));

// Values that are exactly the decimal keep their result.
var_dump(round(0.9, 1, RoundingMode::TowardsZero));
var_dump(round(0.285, 2, RoundingMode::TowardsZero));
var_dump(round(-0.285, 2, RoundingMode::TowardsZero));
var_dump(round(0.285, 2));

?>
--EXPECT--
float(0.8)
float(3.46)
float(0.79)
float(-0.8)
float(-0.8)
float(0.9)
float(0.9)
float(0.28)
float(-0.28)
float(0.29)
