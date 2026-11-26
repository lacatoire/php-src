--TEST--
Test syslog() function : nul byte in message with syslog.filter=all
--SKIPIF--
<?php
if(substr(PHP_OS, 0, 3) == "WIN")
  die("skip Won't run on Windows");
?>
--INI--
syslog.filter=all
--FILE--
<?php
openlog('PHPT', LOG_PERROR, LOG_USER);
syslog(LOG_WARNING, "A simple \0 message\twith a tab");
?>
--EXPECTF--
%SPHPT%S%r(:|-)%r A simple \x00 message	with a tab
