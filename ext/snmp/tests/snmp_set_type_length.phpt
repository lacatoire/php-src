--TEST--
snmp2_set(): the type must be a single character for an array of OIDs too
--EXTENSIONS--
snmp
--FILE--
<?php
$oid = '.1.3.6.1.2.1.1.4.0';
foreach ([[$oid, 'ss'], [$oid, ['ss']], [[$oid], ['ss']], [[$oid], 'ss'], [[$oid], 'ipaddress']] as [$o, $t]) {
    try {
        var_dump(snmp2_set('127.0.0.1', 'private', $o, $t, 'x', 100000, 0));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
ValueError: snmp2_set(): Argument #4 ($type) must be a single character
TypeError: Type must be of type string when object ID is a string
ValueError: snmp2_set(): Argument #4 ($type) must be a single character
ValueError: snmp2_set(): Argument #4 ($type) must be a single character
ValueError: snmp2_set(): Argument #4 ($type) must be a single character
