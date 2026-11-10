--TEST--
date(), DateTime::format(): 'c' and 'r' pad the magnitude of a negative year like 'Y'
--FILE--
<?php
date_default_timezone_set('UTC');
$ts = mktime(0, 0, 0, 6, 28, -1);
echo date('Y', $ts), "\n";
echo date('c', $ts), "\n";
echo date('r', $ts), "\n";

$d = new DateTime('0005-01-01');
$d->modify('-10 years');
echo $d->format('Y'), "\n";
echo $d->format('c'), "\n";
echo $d->format('r'), "\n";

$d = (new DateTime('2000-01-01'))->setDate(12345, 1, 1);
echo $d->format('c'), "\n";
echo $d->format('r'), "\n";
?>
--EXPECT--
-0001
-0001-06-28T00:00:00+00:00
Mon, 28 Jun -0001 00:00:00 +0000
-0005
-0005-01-01T00:00:00+00:00
Sun, 01 Jan -0005 00:00:00 +0000
12345-01-01T00:00:00+00:00
Mon, 01 Jan 12345 00:00:00 +0000
