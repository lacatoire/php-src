--TEST--
snmp3_set(): a BITS value with a large bit number is refused
--EXTENSIONS--
snmp
--FILE--
<?php
foreach (['99999999', '1 1000000', '4096', '-1'] as $value) {
    var_dump(snmp3_set('127.0.0.1', 'noAuthUser', 'noAuthNoPriv', '', '', '', '', '.1.3.6.1.2.1.1.4.0', 'b', $value, 100000, 0));
}
echo "survived\n";
?>
--EXPECTF--
Warning: snmp3_set(): Could not add variable: OID='%s' type='b' value='99999999': %s in %s on line %d
bool(false)

Warning: snmp3_set(): Could not add variable: OID='%s' type='b' value='1 1000000': %s in %s on line %d
bool(false)

Warning: snmp3_set(): Could not add variable: OID='%s' type='b' value='4096': %s in %s on line %d
bool(false)

Warning: snmp3_set(): Could not add variable: OID='%s' type='b' value='-1': %s in %s on line %d
bool(false)
survived
