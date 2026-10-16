--TEST--
strftime(), gmstrftime(): %s is the timestamp whatever the process time zone
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip no TZ environment on Windows');
?>
--ENV--
TZ=Asia/Tokyo
--INI--
date.timezone=UTC
--FILE--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
echo gmstrftime('%s', 0), ' ', gmstrftime('%s', 1000000), ' ', strftime('%s', 1000000), "\n";
echo gmstrftime('100%%s|%s|%%%s|%', 5), "\n";
date_default_timezone_set('America/New_York');
echo strftime('%s %H', 1000000), "\n";
?>
--EXPECT--
0 1000000 1000000
100%s|5|%5|%
1000000 08
