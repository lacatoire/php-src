--TEST--
snmp: unbracketed IPv6 hostname and strict port parsing
--EXTENSIONS--
snmp
--SKIPIF--
<?php
require_once(__DIR__.'/skipif.inc');

$packed = str_repeat(chr(0), 15) . chr(1);
if (@inet_ntop($packed) === false) {
    die("skip no IPv6 support");
}
if (PHP_OS_FAMILY === 'Windows') die('xfail SNMP tests might possibly fail on Windows');
?>
--FILE--
<?php
require_once(__DIR__.'/snmp_include.inc');

snmp_set_quick_print(false);
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);

$oid = '.1.3.6.1.2.1.1.1.0';
var_dump(gettype(snmp2_get($hostname6, $community, $oid, $timeout, $retries)));

foreach (["$hostname4:abc", "$hostname4:161x", "$hostname4:4294967457", "[$hostname6]:abc"] as $host) {
    try {
        snmp2_get($host, $community, $oid, $timeout, $retries);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
string(6) "string"
snmp2_get(): Argument #1 ($hostname) remote port must be between 0 and 65535
snmp2_get(): Argument #1 ($hostname) remote port must be between 0 and 65535
snmp2_get(): Argument #1 ($hostname) remote port must be between 0 and 65535
snmp2_get(): Argument #1 ($hostname) remote port must be between 0 and 65535
