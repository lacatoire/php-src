--TEST--
SNMP_OID_OUTPUT_NONE does not reuse stale buffer contents as OID names
--EXTENSIONS--
snmp
--SKIPIF--
<?php
require_once(__DIR__.'/skipif.inc');
if (getenv('SKIP_ASAN')) die('skip Timeouts under ASAN');
if (PHP_OS_FAMILY === 'Windows') die('xfail SNMP tests might possibly fail on Windows');
?>
--FILE--
<?php
require_once(__DIR__.'/snmp_include.inc');

snmp_set_oid_output_format(SNMP_OID_OUTPUT_NONE);

// The text of the noSuchObject varbind must not become the key of the next one
var_dump(array_keys(snmp2_get($hostname, $community, ['.1.3.6.1.99.99.0', '.1.3.6.1.2.1.1.4.0'], $timeout, $retries)));
var_dump(snmp2_get($hostname, $community, '.1.3.6.1.99.99.0', $timeout, $retries));

?>
--EXPECTF--
Warning: snmp2_get(): Error in packet at '': No Such Object available on this agent at this OID in %s on line %d
array(1) {
  [0]=>
  string(0) ""
}

Warning: snmp2_get(): Error in packet at '': No Such Object available on this agent at this OID in %s on line %d
bool(false)
