--TEST--
hexdec(), octdec(), bindec(): a float result is the double nearest to the exact value
--FILE--
<?php
error_reporting(E_ALL & ~E_NOTICE);
printf("%.17g\n", @hexdec("b50d857da0fb040a18b0"));
printf("%.17g\n", @hexdec("a261fdac2b092402b9d30af4a29d90bcf7c5ef"));
printf("%.17g\n", @bindec(str_repeat("1", 64)));
printf("%.17g\n", @bindec("1" . str_repeat("0", 63) . "1"));
printf("%.17g\n", @bindec("1" . str_repeat("0", 52) . "1" . str_repeat("0", 10) . "1"));
var_dump(@hexdec(str_repeat("f", 300)));
var_dump(@hexdec("7fffffffffffffff"));
?>
--EXPECT--
8.5499776009374317e+23
3.6212569341868424e+45
1.8446744073709552e+19
1.8446744073709552e+19
1.8446744073709556e+19
float(INF)
int(9223372036854775807)