--TEST--
unixtojd(): the day is computed in the time zone of PHP, not the process one
--EXTENSIONS--
calendar
--ENV--
TZ=Pacific/Auckland
--FILE--
<?php
// 1970-01-01 23:59:59 UTC
foreach (['UTC', 'Asia/Tokyo', 'America/Los_Angeles'] as $tz) {
    date_default_timezone_set($tz);
    echo $tz, ': ', date('Y-m-d H:i:s', 86399), ' ', unixtojd(86399), "\n";
}
date_default_timezone_set('America/Los_Angeles');
var_dump(unixtojd(0));
date_default_timezone_set('UTC');
var_dump(unixtojd(0));
?>
--EXPECT--
UTC: 1970-01-01 23:59:59 2440588
Asia/Tokyo: 1970-01-02 08:59:59 2440589
America/Los_Angeles: 1970-01-01 15:59:59 2440588
int(2440587)
int(2440588)
