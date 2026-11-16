--TEST--
rad2deg() does not lose precision for subnormal inputs
--FILE--
<?php
var_dump(rad2deg(4.9e-324));
var_dump(rad2deg(1e-323));
var_dump(rad2deg(1e-320));
var_dump(rad2deg(-1e-320));
?>
--EXPECT--
float(2.8E-322)
float(5.7E-322)
float(5.72953E-319)
float(-5.72953E-319)
