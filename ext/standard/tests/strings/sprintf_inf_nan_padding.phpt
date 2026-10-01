--TEST--
sprintf(): INF and NAN honour the field width and sign flag with float conversions
--FILE--
<?php
foreach (['%+010f', '%+10f', '%010f', '%10f', '%-10f|', '%+010e', '%+010g', '%5.1f'] as $format) {
    foreach ([INF, -INF, NAN] as $value) {
        echo $format, ' ', var_export(sprintf($format, $value), true), "\n";
    }
}
?>
--EXPECT--
%+010f '      +INF'
%+010f '      -INF'
%+010f '       NaN'
%+10f '      +INF'
%+10f '      -INF'
%+10f '       NaN'
%010f '       INF'
%010f '      -INF'
%010f '       NaN'
%10f '       INF'
%10f '      -INF'
%10f '       NaN'
%-10f| 'INF       |'
%-10f| '-INF      |'
%-10f| 'NaN       |'
%+010e '      +INF'
%+010e '      -INF'
%+010e '       NaN'
%+010g '      +INF'
%+010g '      -INF'
%+010g '       NaN'
%5.1f '  INF'
%5.1f ' -INF'
%5.1f '  NaN'
